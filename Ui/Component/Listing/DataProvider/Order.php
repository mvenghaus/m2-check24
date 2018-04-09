<?php

namespace Inkl\Check24\Ui\Component\Listing\DataProvider;

use Magento\Ui\DataProvider\AbstractDataProvider;

class Order extends AbstractDataProvider
{
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Inkl\Check24\Model\ResourceModel\Order\CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }
}
