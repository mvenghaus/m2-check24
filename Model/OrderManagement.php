<?php

namespace Inkl\Check24\Model;

use Inkl\AddressUtils\StreetSplitter;
use Inkl\Check24\Api\OrderManagementInterface;
use Inkl\Check24\Helper\Config\GeneralConfig;
use Inkl\Check24\Helper\Config\OrderConfig;
use Magento\Store\Model\StoreManagerInterface;

class OrderManagement implements OrderManagementInterface
{
    /** @var StoreManagerInterface */
    private $storeManager;
    /** @var GeneralConfig */
    private $generalConfig;
    /** @var OrderConfig */
    private $orderConfig;

    /**
     * @param StoreManagerInterface $storeManager
     * @param GeneralConfig $generalConfig
     * @param OrderConfig $orderConfig
     */
    public function __construct(StoreManagerInterface $storeManager,
                                GeneralConfig $generalConfig,
                                OrderConfig $orderConfig)
    {
        $this->storeManager = $storeManager;
        $this->generalConfig = $generalConfig;
        $this->orderConfig = $orderConfig;
    }

    public function getStoreIdByPartnerId($partnerId)
    {
        foreach ($this->storeManager->getStores() as $store)
        {
            if ($this->generalConfig->getPartnerId($store->getId()) == $partnerId)
            {
                return $store->getId();
            }
        }

        return null;
    }

    public function buildStreetData($street, $remarks, $storeId)
    {
        $streetData = [];
        if ($this->orderConfig->shouldSplitStreet($storeId))
        {
            $parts = (new StreetSplitter())->split($street);
            foreach ($parts as $part)
            {
                $streetData[] = $part;
            }
        } else
        {
            $streetData[] = $street;
        }

        if ($remarks)
        {
            $streetData[] = $remarks;
        }

        return $streetData;
    }

}