<?php

namespace Inkl\Check24\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface OrderSearchResultsInterface extends SearchResultsInterface
{

	/**
	 * Get items list.
	 *
	 * @return OrderInterface[]
	 */
	public function getItems();

	/**
	 * Set items list.
	 *
	 * @param OrderInterface[] $items
	 * @return $this
	 */
	public function setItems(array $items);

}