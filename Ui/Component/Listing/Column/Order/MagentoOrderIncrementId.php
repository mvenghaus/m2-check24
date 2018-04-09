<?php

namespace Inkl\Check24\Ui\Component\Listing\Column\Order;

use Magento\Ui\Component\Listing\Columns\Column;

class MagentoOrderIncrementId extends Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items']))
        {
            foreach ($dataSource['data']['items'] as &$item)
            {
                $magentoOrderId = $item['magento_order_id'] ?? null;
                $magentoOrderIncrementId = $item['magento_order_increment_id'] ?? null;

                if ($magentoOrderId && $magentoOrderIncrementId)
                {
                    $item['magento_order_increment_id'] = sprintf('<a href="%s">%s</a>', $this->context->getUrl('sales/order/view', ['order_id' => $item['magento_order_id']]), $item['magento_order_increment_id']);
                }
            }
        }

        return $dataSource;
    }

}
