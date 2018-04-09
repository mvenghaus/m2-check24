<?php

namespace Inkl\Check24\Helper\Config;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class FtpConfig extends AbstractHelper
{

    const XML_PATH_HOST = 'inkl_check24/ftp/host';
    const XML_PATH_USER = 'inkl_check24/ftp/user';
    const XML_PATH_PASSWORD = 'inkl_check24/ftp/password';
    const XML_PATH_PORT = 'inkl_check24/ftp/port';

    public function getHost($storeId = null)
    {
        return $this->scopeConfig->getValue(self::XML_PATH_HOST, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getUser($storeId = null)
    {
        return $this->scopeConfig->getValue(self::XML_PATH_USER, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getPassword($storeId = null)
    {
        return $this->scopeConfig->getValue(self::XML_PATH_PASSWORD, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getPort($storeId = null)
    {
        return $this->scopeConfig->getValue(self::XML_PATH_PORT, ScopeInterface::SCOPE_STORE, $storeId);
    }

}
