<?php

namespace Inkl\Check24\Api;

interface OrderManagementInterface
{

    public function getStoreIdByPartnerId($partnerId);

    public function buildStreetData($street, $remarks, $storeId);

}