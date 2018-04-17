<?php

namespace Inkl\Check24\Model;


use Inkl\Check24\Api\OrderProviderInterface;
use Inkl\Check24\Api\OrderRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class OrderProvider implements OrderProviderInterface
{
    /** @var OrderRepositoryInterface */
    private $orderRepository;
    /** @var SearchCriteriaBuilder */
    private $searchCriteriaBuilder;

    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(OrderRepositoryInterface $orderRepository,
                                SearchCriteriaBuilder $searchCriteriaBuilder)
    {

        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function getNotProcessedList()
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('processed', 0)
            ->addFilter('error', 1, 'neq')
            ->create();

        return $this->orderRepository->getList($searchCriteria);
    }

}