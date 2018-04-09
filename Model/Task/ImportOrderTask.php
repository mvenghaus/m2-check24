<?php

namespace Inkl\Check24\Model\Task;

use Inkl\Check24\Api\Data\OrderInterfaceFactory;
use Inkl\Check24\Api\OrderRepositoryInterface;
use Inkl\Check24\Helper\Config\FtpConfig;
use Inkl\Check24\Helper\Config\GeneralConfig;
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

    /**
     * @param GeneralConfig $generalConfig
     * @param FtpConfig $ftpConfig
     * @param StoreManagerInterface $storeManager
     * @param OrderInterfaceFactory $orderFactory
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(GeneralConfig $generalConfig,
                                FtpConfig $ftpConfig,
                                StoreManagerInterface $storeManager,
                                OrderInterfaceFactory $orderFactory,
                                OrderRepositoryInterface $orderRepository)
    {
        $this->generalConfig = $generalConfig;
        $this->ftpConfig = $ftpConfig;
        $this->storeManager = $storeManager;
        $this->orderFactory = $orderFactory;
        $this->orderRepository = $orderRepository;
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

            if ($ftp->open($ftpAccount))
            {
                $ftp->cd('outbound');
                foreach ($ftp->ls() as $file)
                {
                    if (preg_match('/-ORDER\.xml$/is', $file['text']))
                    {
                        $fileContent = $ftp->read($file['text']);
                        if ($fileContent)
                        {
                            $fileContent = iconv('ISO-8859-15', 'UTF-8', $fileContent);
                            $fileContent = str_replace('ISO-8859-15', 'UTF-8', $fileContent);

                            $this->orderRepository->save(
                                $this->orderFactory->create()
                                    ->setFilename($file['text'])
                                    ->setContent($fileContent)
                            );
                            $ftp->rm($file['text']);
                        }
                    }

                    if (preg_match('/-DISPATCHNOTIFICATION\.xml$/is', $file['text']))
                    {
                        $ftp->rm($file['text']);
                    }
                }

                $ftp->close();
            }
        } catch (\Exception $e)
        {
            print_r($e->getMessage());
            exit;
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
