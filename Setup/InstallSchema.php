<?php

namespace Inkl\Check24\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $tableName = 'check24_orders';
        $table = $setup->getConnection()
            ->newTable($setup->getTable($tableName))
            ->addColumn('id', Table::TYPE_INTEGER, null, ['primary' => true, 'identity' => true, 'nullable' => false])
            ->addColumn('filename', Table::TYPE_TEXT, 255, [], 'Filename')
            ->addColumn('content', Table::TYPE_TEXT, null, [], 'Content')
            ->addColumn('processed', Table::TYPE_INTEGER, 1, ['default' => 0], 'Processed')
            ->addColumn('magento_order_id', Table::TYPE_INTEGER, null, [], 'Magento Order ID')
            ->addColumn('magento_order_increment_id', Table::TYPE_TEXT, 32, [], 'Magento Order Increment ID')
            ->addColumn('error', Table::TYPE_INTEGER, 1, ['default' => 0], 'Error')
            ->addColumn('error_message', Table::TYPE_TEXT, null, [], 'Error Message')
            ->addColumn('updated_at', Table::TYPE_DATETIME, null, [], 'Updated At')
            ->addColumn('created_at', Table::TYPE_DATETIME, null, [], 'Created At')
            ->addIndex('IDX_MAGENTO_ORDER_ID', ['magento_order_id'], ['type' => AdapterInterface::INDEX_TYPE_INDEX]);

        $setup->getConnection()->dropTable($tableName);
        $setup->getConnection()->createTable($table);
    }

}