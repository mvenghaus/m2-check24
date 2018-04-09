<?php

namespace Inkl\Check24\Api;

use Inkl\Check24\Api\Data\OrderInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface OrderRepositoryInterface
{

    public function save(OrderInterface $order);

    public function load($id);

    public function delete(OrderInterface $order);

    public function getList(SearchCriteriaInterface $searchCriteria);

}