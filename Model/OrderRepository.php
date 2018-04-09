<?php

namespace Inkl\Check24\Model;

use Inkl\Check24\Api\Data\OrderInterface;
use Inkl\Check24\Api\Data\OrderInterfaceFactory;
use Inkl\Check24\Api\Data\OrderSearchResultsInterfaceFactory;
use Inkl\Check24\Api\OrderRepositoryInterface;
use Inkl\Check24\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Stdlib\DateTime\DateTimeFactory;

class OrderRepository implements OrderRepositoryInterface
{
    /** @var OrderInterfaceFactory */
    private $orderFactory;
    /** @var OrderCollectionFactory */
    private $orderCollectionFactory;
    /** @var OrderSearchResultsInterfaceFactory */
    private $orderSearchResultsFactory;
    /** @var SearchCriteriaBuilder */
    private $searchCriteriaBuilder;
    /** @var CollectionProcessorInterface */
    private $collectionProcessor;
    /** @var DateTimeFactory */
    private $dateTimeFactory;

    /**
     * @param OrderInterfaceFactory $orderFactory
     * @param OrderCollectionFactory $orderCollectionFactory
     * @param OrderSearchResultsInterfaceFactory $orderSearchResultsFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param CollectionProcessorInterface $collectionProcessor
     * @param DateTimeFactory $dateTimeFactory
     */
    public function __construct(OrderInterfaceFactory $orderFactory,
                                OrderCollectionFactory $orderCollectionFactory,
                                OrderSearchResultsInterfaceFactory $orderSearchResultsFactory,
                                SearchCriteriaBuilder $searchCriteriaBuilder,
                                CollectionProcessorInterface $collectionProcessor,
                                DateTimeFactory $dateTimeFactory)
    {
        $this->orderFactory = $orderFactory;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->orderSearchResultsFactory = $orderSearchResultsFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->collectionProcessor = $collectionProcessor;
        $this->dateTimeFactory = $dateTimeFactory;
    }

    public function save(OrderInterface $order)
    {
        if ($order->isObjectNew()) {
            $order->setCreatedAt($this->dateTimeFactory->create()->gmtDate());
        } else {
            $order->setUpdatedAt($this->dateTimeFactory->create()->gmtDate());
        }

        return $this->load($order->getId())
            ->setData($order->getData())
            ->save();
    }

    public function load($id)
    {
        return $this->orderFactory
            ->create()
            ->load($id);
    }

    public function delete(OrderInterface $order)
    {
        return $this
            ->load($order->getId())
            ->delete();
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Inkl\Check24\Model\ResourceModel\Order\Collection $collection */
        $collection = $this->orderCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResult = $this->orderSearchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());

        return $searchResult;
    }

}