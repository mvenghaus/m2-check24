<?php

namespace Inkl\Check24\Helper\Config;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class GeneralConfig extends AbstractHelper
{
    const XML_PATH_ENABLED = 'inkl_check24/general/enabled';
    const XML_PATH_PARTNER_ID = 'inkl_check24/general/partner_id';
    const XML_PATH_NOTIFICATION_EMAIL = 'inkl_check24/general/notification_email';

    public function isEnabled($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_ENABLED, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getPartnerId($storeId = null)
    {
        return $this->scopeConfig->getValue(self::XML_PATH_PARTNER_ID, ScopeInterface::SCOPE_STORE, $storeId);
    }

}
