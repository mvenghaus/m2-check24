<?php

namespace Inkl\Check24\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Order extends AbstractDb
{
	protected function _construct()
	{
		$this->_init('check24_orders', 'id');
	}
}