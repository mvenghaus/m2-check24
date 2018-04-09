<?php

namespace Inkl\Check24\Model\ResourceModel\Order;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

	protected $_eventPrefix = 'inkl_check24_order_collection';
	protected $_eventObject = 'order_collection';

	protected function _construct()
	{
		$this->_init('Inkl\Check24\Model\Order', 'Inkl\Check24\Model\ResourceModel\Order');
	}
}