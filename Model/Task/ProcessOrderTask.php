<?php

namespace Inkl\Check24\Model\Task;

use Inkl\Check24\Api\OrderManagementInterface;
use Inkl\Check24\Api\OrderProviderInterface;
use Inkl\Check24\Api\OrderRepositoryInterface;
use Inkl\Check24\Helper\Config\OrderConfig;
use Inkl\Check24\Model\Reader\OpenTransOrder;
use Inkl\SystemMail\Model\Sender\MailSender;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteRepository;
use Magento\Sales\Api\OrderManagementInterface as MagentoOrderManagementInterface;
use Magento\Sales\Api\OrderRepositoryInterface as MagentoOrderRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\App\Emulation as AppEmulation;

class ProcessOrderTask
{
    /** @var AppEmulation */
    private $appEmulation;
    /** @var OrderProviderInterface */
    private $orderProvider;
    /** @var OrderManagementInterface */
    private $orderManagement;
    /** @var OpenTransOrder */
    private $openTransOrder;
    /** @var StoreManagerInterface */
    private $storeManager;
    /** @var CartRepositoryInterface */
    private $cartRepository;
    /** @var CartManagementInterface */
    private $cartManagement;
    /** @var ProductRepositoryInterface */
    private $productRepository;
    /** @var OrderConfig */
    private $orderConfig;
    /** @var QuoteRepository */
    private $quoteRepository;
    /** @var MagentoOrderRepositoryInterface */
    private $magentoOrderRepository;
    /** @var MagentoOrderManagementInterface */
    private $magentoOrderManagement;
    /** @var OrderRepositoryInterface */
    private $orderRepository;
    /** @var MailSender */
    private $mailSender;

    /**
     * @param AppEmulation $appEmulation
     * @param OrderProviderInterface $orderProvider
     * @param OrderManagementInterface $orderManagement
     * @param OrderRepositoryInterface $orderRepository
     * @param OpenTransOrder $openTransOrder
     * @param StoreManagerInterface $storeManager
     * @param CartRepositoryInterface $cartRepository
     * @param CartManagementInterface $cartManagement
     * @param ProductRepositoryInterface $productRepository
     * @param OrderConfig $orderConfig
     * @param QuoteRepository $quoteRepository
     * @param MagentoOrderRepositoryInterface $magentoOrderRepository
     * @param MagentoOrderManagementInterface $magentoOrderManagement
     * @param MailSender $mailSender
     */
    public function __construct(AppEmulation $appEmulation,
                                OrderProviderInterface $orderProvider,
                                OrderManagementInterface $orderManagement,
                                OrderRepositoryInterface $orderRepository,
                                OpenTransOrder $openTransOrder,
                                StoreManagerInterface $storeManager,
                                CartRepositoryInterface $cartRepository,
                                CartManagementInterface $cartManagement,
                                ProductRepositoryInterface $productRepository,
                                OrderConfig $orderConfig,
                                QuoteRepository $quoteRepository,
                                MagentoOrderRepositoryInterface $magentoOrderRepository,
                                MagentoOrderManagementInterface $magentoOrderManagement,
                                MailSender $mailSender
    )
    {
        $this->appEmulation = $appEmulation;
        $this->orderProvider = $orderProvider;
        $this->orderManagement = $orderManagement;
        $this->openTransOrder = $openTransOrder;
        $this->storeManager = $storeManager;
        $this->cartRepository = $cartRepository;
        $this->cartManagement = $cartManagement;
        $this->productRepository = $productRepository;
        $this->orderConfig = $orderConfig;
        $this->quoteRepository = $quoteRepository;
        $this->magentoOrderRepository = $magentoOrderRepository;
        $this->magentoOrderManagement = $magentoOrderManagement;
        $this->orderRepository = $orderRepository;
        $this->mailSender = $mailSender;
    }

    public function run()
    {
        $orderList = $this->orderProvider->getNotProcessedList();
        if (!$orderList->getTotalCount())
        {
            return;
        }

        foreach ($orderList->getItems() as $order)
        {
            $openTransOrder = $this->openTransOrder->load($order->getContent());

            try
            {
                $partnerId = $openTransOrder->getPartnerId();
                $storeId = $this->orderManagement->getStoreIdByPartnerId($partnerId);
                if (!$storeId)
                {
                    throw new \Exception(sprintf('no matching store id for partner id "%s"', $partnerId));
                }

                $this->appEmulation->startEnvironmentEmulation($storeId);

                $magentoOrder = $this->createMagentoOrder($openTransOrder);

                $order
                    ->setProcessed(true)
                    ->setMagentoOrderId($magentoOrder->getId())
                    ->setMagentoOrderIncrementId($magentoOrder->getIncrementId());

                $this->orderRepository->save($order);

                $this->appEmulation->stopEnvironmentEmulation();
            } catch (\Exception $e)
            {
                $order
                    ->setError(true)
                    ->setErrorMessage($e->getMessage());

                $this->orderRepository->save($order);

                $this->mailSender->sendCritical('Check24 Bestellung', $e->getMessage());
            }
        }
    }

    private function createMagentoOrder(OpenTransOrder $openTransOrder)
    {
        $cartId = $this->cartManagement->createEmptyCart();

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->cartRepository->get($cartId);

        $this->setBaseData($quote, $openTransOrder);
        $this->setBillingAddress($quote, $openTransOrder);
        $this->setShippingAddress($quote, $openTransOrder);
        $this->setOrderItems($quote, $openTransOrder);
        $this->setShippingMethod($quote);
        $this->setPaymentMethod($quote);

        $this->quoteRepository->save($quote);

        $quote->collectTotals();

        $orderId = $this->cartManagement->placeOrder($quote->getId());

        $order = $this->magentoOrderRepository->get($orderId);

        $this->magentoOrderManagement->notify($orderId);

        return $order;
    }

    private function setBaseData(Quote $quote, OpenTransOrder $openTransOrder)
    {
        $quote
            ->setStore($this->storeManager->getStore())
            ->setCurrency()
            ->setCustomerNote($openTransOrder->getOrderId())
            ->setCustomerIsGuest(true)
            ->setCustomerEmail($openTransOrder->getInvoiceEmail())
            ->setCustomerFirstname($openTransOrder->getInvoiceFirstname())
            ->setCustomerLastname($openTransOrder->getInvoiceLastname())
            ->setInventoryProcessed(false);
    }

    private function setBillingAddress(Quote $quote, OpenTransOrder $openTransOrder)
    {
        $quote->getBillingAddress()
            ->setCompany('')
            ->setFirstname('Dies ist')
            ->setLastname('eine Proformarechnung')
            ->setStreet('und berechtigt nicht')
            ->setPostcode('zum')
            ->setCity('Vorsteuerabzug.')
            ->setCountryId($openTransOrder->getInvoiceCountryCode())
            ->setRegionId($openTransOrder->getInvoiceRegionId())
            ->setTelephone($openTransOrder->getInvoicePhone());
    }

    private function setShippingAddress(Quote $quote, OpenTransOrder $openTransOrder)
    {
        $quote->getShippingAddress()
            ->setCompany($openTransOrder->getDeliveryCompany())
            ->setFirstname($openTransOrder->getDeliveryFirstname())
            ->setLastname($openTransOrder->getDeliveryLastname())
            ->setStreet($this->orderManagement->buildStreetData($openTransOrder->getDeliveryStreet(), $openTransOrder->getDeliveryRemarks(), $quote->getStoreId()))
            ->setPostcode($openTransOrder->getDeliveryPostcode())
            ->setCity($openTransOrder->getDeliveryCity())
            ->setCountryId($openTransOrder->getDeliveryCountryCode())
            ->setRegionId($openTransOrder->getDeliveryRegionId())
            ->setTelephone($openTransOrder->getDeliveryPhone());
    }

    private function setOrderItems(Quote $quote, OpenTransOrder $openTransOrder)
    {
        foreach ($openTransOrder->getOrderItems() as $orderItem)
        {
            $product = $this->productRepository->get($orderItem['sku'], false, $quote->getStoreId());
            $product
                ->setName($orderItem['name'])
                ->setPrice($orderItem['price']);

            $quote->addProduct($product, intval($orderItem['qty']));
        }
    }

    private function setShippingMethod(Quote $quote)
    {
        $quote->getShippingAddress()
            ->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod($this->orderConfig->getShippingCarrier($quote->getStoreId()));
    }

    private function setPaymentMethod(Quote $quote)
    {
        $quote->setPaymentMethod('check24');
        $quote->getPayment()->importData(['method' => 'check24']);
    }
}