<?php

namespace Inkl\Check24\Ui\Component\Listing\Column\Order;

use Magento\Ui\Component\Listing\Columns\Column;

class Comment extends Column
{

	public function prepareDataSource(array $dataSource)
	{
		if (isset($dataSource['data']['items']))
		{
			foreach ($dataSource['data']['items'] as &$item)
			{
				$item['comment_save_url'] = $this->context->getUrl('inkl_check24/order/saveComment', ['id' => $item['id']]);
			}
		}

		return $dataSource;
	}

}
