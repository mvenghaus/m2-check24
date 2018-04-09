<?php

namespace Inkl\Check24\Helper\Config;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class OrderConfig extends AbstractHelper
{

    const XML_PATH_SHIPPING_CARRIER = 'inkl_check24/order/shipping_carrier';
    const XML_PATH_SPLIT_STREET = 'inkl_check24/order/split_street';

    public function shouldSplitStreet($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_SPLIT_STREET, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getShippingCarrier($storeId = null)
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SHIPPING_CARRIER, ScopeInterface::SCOPE_STORE, $storeId);
    }

}
