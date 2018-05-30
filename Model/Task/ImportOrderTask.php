<?php

namespace Inkl\Check24\Model\Task;

use Inkl\Check24\Api\Data\OrderInterfaceFactory;
use Inkl\Check24\Api\OrderProviderInterface;
use Inkl\Check24\Api\OrderRepositoryInterface;
use Inkl\Check24\Helper\Config\FtpConfig;
use Inkl\Check24\Helper\Config\GeneralConfig;
use Inkl\Check24\Logger\Logger;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;

class ImportOrderTask
{
    /** @var GeneralConfig */
    private $generalConfig;
    /** @var FtpConfig */
    private $ftpConfig;
    /** @var StoreManagerInterface */
    private $storeManager;
    /** @var OrderInterfaceFactory */
    private $orderFactory;
    /** @var OrderRepositoryInterface */
    private $orderRepository;
    /** @var OrderProviderInterface */
    private $orderProvider;
    /** @var Logger */
    private $logger;

    /**
     * @param GeneralConfig $generalConfig
     * @param FtpConfig $ftpConfig
     * @param StoreManagerInterface $storeManager
     * @param OrderInterfaceFactory $orderFactory
     * @param OrderRepositoryInterface $orderRepository
     * @param OrderProviderInterface $orderProvider
     * @param Logger $logger
     */
    public function __construct(GeneralConfig $generalConfig,
                                FtpConfig $ftpConfig,
                                StoreManagerInterface $storeManager,
                                OrderInterfaceFactory $orderFactory,
                                OrderRepositoryInterface $orderRepository,
                                OrderProviderInterface $orderProvider,
                                Logger $logger
    )
    {
        $this->generalConfig = $generalConfig;
        $this->ftpConfig = $ftpConfig;
        $this->storeManager = $storeManager;
        $this->orderFactory = $orderFactory;
        $this->orderRepository = $orderRepository;
        $this->orderProvider = $orderProvider;
        $this->logger = $logger;
    }

    public function run()
    {
        $ftpAccounts = $this->getFtpAccounts();
        foreach ($ftpAccounts as $ftpAccount)
        {
            $this->importFiles($ftpAccount);
        }
    }

    private function importFiles(array $ftpAccount)
    {

        $ftp = new \Magento\Framework\Filesystem\Io\Ftp();

        try
        {
            $ftpAccount['passive'] = true;
            $ftpAccount['timeout'] = 20;

            $this->logger->debug(sprintf('trying to connect to ftp %s@%s:%s', $ftpAccount['user'], $ftpAccount['host'], $ftpAccount['port']));

            if ($ftp->open($ftpAccount))
            {
                $this->logger->debug('ftp connection established');

                $ftp->cd('outbound');
                foreach ($ftp->ls() as $file)
                {
                    $filename = $file['text'];

                    $this->logger->debug(sprintf('checking file "%s"', $filename));

                    if (preg_match('/-ORDER\.xml$/is', $filename))
                    {
                        $fileContent = $ftp->read($filename);
                        if ($fileContent)
                        {
                            $fileContent = iconv('ISO-8859-15', 'UTF-8', $fileContent);
                            $fileContent = str_replace('ISO-8859-15', 'UTF-8', $fileContent);

                            $order = $this->orderProvider->getByFilename($filename);
                            if (!$order)
                            {
                                $this->logger->debug('creating order');

                                $order = $this->orderRepository->save(
                                    $this->orderFactory->create()
                                        ->setFilename($file['text'])
                                        ->setContent($fileContent)
                                );
                            } else {
                                $this->logger->debug('order with this filename already exists');
                            }

                            $this->logger->debug('checking if order has a valid id');

                            if ($order->getId() > 0)
                            {
                                $this->logger->debug('ok -> deleting file');

                                $ftp->rm($filename);
                            } else {
                                $this->logger->debug('error -> keeping file');
                            }
                        }
                    }

                    if (preg_match('/-DISPATCHNOTIFICATION\.xml$/is', $filename))
                    {
                        $this->logger->debug('is notification file -> delete');

                        $ftp->rm($filename);
                    }
                }

                $ftp->close();
            }
        } catch (\Exception $e)
        {
            $this->logger->debug(sprintf('EXCEPTION!! - %s', $e->getMessage()));
        }
    }

    private function getFtpAccounts()
    {
        $ftpAccounts = [];

        foreach ($this->storeManager->getStores() as $store)
        {
            if (!$this->hasValidSettings($store))
            {
                continue;
            }

            $ftpAccount = [
                'host' => $this->ftpConfig->getHost($store->getId()),
                'user' => $this->ftpConfig->getUser($store->getId()),
                'password' => $this->ftpConfig->getPassword($store->getId()),
                'port' => $this->ftpConfig->getPort($store->getId()),
            ];

            $key = implode('#', $ftpAccount);
            if (!isset($ftpAccounts[$key]))
            {
                $ftpAccounts[$key] = $ftpAccount;
            }
        }

        return $ftpAccounts;
    }

    private function hasValidSettings(StoreInterface $store)
    {
        return ($this->generalConfig->isEnabled($store->getId()) &&
            $this->ftpConfig->getHost($store->getId()) &&
            $this->ftpConfig->getUser($store->getId()) &&
            $this->ftpConfig->getPassword($store->getId()) &&
            $this->ftpConfig->getPort($store->getId()));
    }
}
