<?php

namespace Inkl\Check24\Controller\Adminhtml\Order;

use Inkl\Check24\Api\OrderRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\NoSuchEntityException;

class Retry extends \Magento\Backend\App\Action
{
    /** @var OrderRepositoryInterface */
    private $orderRepository;

    /**
     * @param Action\Context $context
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(Action\Context $context,
                                OrderRepositoryInterface $orderRepository)
    {
        parent::__construct($context);
        $this->orderRepository = $orderRepository;
    }

    public function execute()
    {
        try
        {
            $id = $this->getRequest()->getParam('id');
            $order = $this->orderRepository->load($id);
            if (!$order->getId() > 0)
            {
                throw new NoSuchEntityException(__(sprintf('order id %d not found', $id)));
            }

            $order
                ->setProcessed(0)
                ->setMagentoOrderId('')
                ->setMagentoOrderIncrementId('')
                ->setError(0)
                ->setErrorMessage('');

            $this->orderRepository->save($order);

            $this->messageManager->addSuccessMessage(__('Success'));
        } catch (\Exception $e)
        {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        $this->_redirect('*/*/index');
    }

}