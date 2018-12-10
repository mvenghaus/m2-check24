<?php

namespace Inkl\Check24\Controller\Adminhtml\Order;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Inkl\Check24\Api\OrderRepositoryInterface;

class SaveComment extends \Magento\Backend\App\Action
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
        /** @var \Magento\Framework\Controller\Result\Json $result */
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        try
        {
            $order = $this->orderRepository->load($this->_request->getParam('id'));

            $order->setComment($this->_request->getPost('comment'));

            $this->orderRepository->save($order);

            return $result->setData([
                'success' => true
            ]);
        } catch (\Exception $e)
        {
            return $result->setData([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }
}