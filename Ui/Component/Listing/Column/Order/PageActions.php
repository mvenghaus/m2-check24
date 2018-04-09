<?php

namespace Inkl\Check24\Ui\Component\Listing\Column\Order;

use Magento\Ui\Component\Listing\Columns\Column;

class PageActions extends Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items']))
        {
            $name = $this->getData('name');
            foreach ($dataSource['data']['items'] as &$item)
            {
                if ($item['error'] == 1)
                {
                    $item[$name]['view'] = [
                        'href' => $this->getContext()->getUrl('inkl_check24/order/retry', ['id' => $item['id']]),
                        'label' => __('Retry')
                    ];
                }
            }
        }

        return $dataSource;
    }

}
