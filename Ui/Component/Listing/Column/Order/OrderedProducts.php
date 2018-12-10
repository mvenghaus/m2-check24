<?php

namespace Inkl\Check24\Ui\Component\Listing\Column\Order;

use Magento\Ui\Component\Listing\Columns\Column;

class OrderedProducts extends Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items']))
        {
            foreach ($dataSource['data']['items'] as &$item)
            {
                $item['ordered_products'] = nl2br($item['ordered_products']);
            }
        }

        return $dataSource;
    }

}
