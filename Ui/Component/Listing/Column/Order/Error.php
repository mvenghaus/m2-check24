<?php

namespace Inkl\Check24\Ui\Component\Listing\Column\Order;

use Magento\Ui\Component\Listing\Columns\Column;

class Error extends Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items']))
        {
            foreach ($dataSource['data']['items'] as &$item)
            {
                $error = $item['error'] ?? null;
                if ($error)
                {
                    $errorMessage = $item['error_message'] ?? null;

                    $item['error'] = sprintf('<strong style="color:red;">%s</strong><br>%s', __('Error'), $errorMessage);
                } else {
                    $item['error'] = '-';
                }
            }
        }

        return $dataSource;
    }

}
