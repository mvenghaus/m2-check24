<?php

namespace Inkl\Check24\Ui\Component\Listing\Column\Order;

use Magento\Ui\Component\Listing\Columns\Column;

class Filename extends Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items']))
        {
            foreach ($dataSource['data']['items'] as &$item)
            {
                $item['filename'] = sprintf('<a href="%s" target="_blank">%s</a>', $this->getContext()->getUrl('inkl_check24/order/view', ['id' => $item['id']]), $item['filename']);
            }
        }

        return $dataSource;
    }

}
