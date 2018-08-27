<?php

namespace Inkl\Check24\Controller\Adminhtml\Order;

use Inkl\Check24\Api\OrderRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class View extends \Magento\Backend\App\Action
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

            $resultPage =$this->resultFactory->create(ResultFactory::TYPE_RAW);
            $resultPage
                ->setHeader('Content-Type', 'text/xml')
                ->setContents($order->getContent());

            return $resultPage;


        } catch (\Exception $e)
        {
            $this->messageManager->addErrorMessage($e->getMessage());

            $this->_redirect('*/*/index');
        }
    }

}