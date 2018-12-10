<?php

namespace Inkl\Check24\Model;

use Inkl\Check24\Api\Data\OrderInterface;
use Magento\Framework\Model\AbstractModel;

class Order extends AbstractModel implements OrderInterface
{

	protected function _construct()
	{
		$this->_init('Inkl\Check24\Model\ResourceModel\Order');
	}

	public function setId($id)
	{
		$this->setData(self::KEY_ID, $id);
		return $this;
	}

	public function getId()
	{
		return $this->getData(self::KEY_ID);
	}

	public function setFilename($filename)
	{
		$this->setData(self::KEY_FILENAME, $filename);
		return $this;
	}

	public function getFilename()
	{
		return $this->getData(self::KEY_FILENAME);
	}

	public function setContent($content)
	{
		$this->setData(self::KEY_CONTENT, $content);
		return $this;
	}

	public function getContent()
	{
		return $this->getData(self::KEY_CONTENT);
	}

	public function setProcessed($processed)
	{
		$this->setData(self::KEY_PROCESSED, $processed);
		return $this;
	}

	public function getProcessed()
	{
		return $this->getData(self::KEY_PROCESSED);
	}

	public function setMagentoOrderId($magentoOrderId)
	{
		$this->setData(self::KEY_MAGENTO_ORDER_ID, $magentoOrderId);
		return $this;
	}

	public function getMagentoOrderId()
	{
		return $this->getData(self::KEY_MAGENTO_ORDER_ID);
	}

	public function setMagentoOrderIncrementId($magentoOrderIncrementId)
	{
		$this->setData(self::KEY_MAGENTO_ORDER_INCREMENT_ID, $magentoOrderIncrementId);
		return $this;
	}

	public function getMagentoOrderIncrementId()
	{
		return $this->getData(self::KEY_MAGENTO_ORDER_INCREMENT_ID);
	}

	public function setOrderedProducts($orderedProducts)
	{
		$this->setData(self::KEY_ORDERED_PRODUCTS, $orderedProducts);
		return $this;
	}

	public function getOrderedProducts()
	{
		return $this->getData(self::KEY_ORDERED_PRODUCTS);
	}

	public function setComment($comment)
	{
		$this->setData(self::KEY_COMMENT, $comment);
		return $this;
	}

	public function getComment()
	{
		return $this->getData(self::KEY_COMMENT);
	}

	public function setError($error)
	{
		$this->setData(self::KEY_ERROR, $error);
		return $this;
	}

	public function getError()
	{
		return $this->getData(self::KEY_ERROR);
	}

	public function setErrorMessage($errorMessage)
	{
		$this->setData(self::KEY_ERROR_MESSAGE, $errorMessage);
		return $this;
	}

	public function getErrorMessage()
	{
		return $this->getData(self::KEY_ERROR_MESSAGE);
	}

	public function setUpdatedAt($updatedAt)
	{
		$this->setData(self::KEY_UPDATED_AT, $updatedAt);
		return $this;
	}

	public function getUpdatedAt()
	{
		return $this->getData(self::KEY_UPDATED_AT);
	}

	public function setCreatedAt($createdAt)
	{
		$this->setData(self::KEY_CREATED_AT, $createdAt);
		return $this;
	}

	public function getCreatedAt()
	{
		return $this->getData(self::KEY_CREATED_AT);
	}

}