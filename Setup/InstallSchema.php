<?php
namespace Godogi\Tuto\Setup;
 
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
 
class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $table = $setup->getConnection()->newTable(
            $setup->getTable('godogi_easteregghunt_eggcus')
        )->addColumn(
            'egg_cus_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Egg Customer Id'
        )->addColumn(
            'customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Customer Id'
        )->addColumn(
            'egg_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Egg Id'
        )->addColumn(
	        'created_at',
	        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
	        null,
	        ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
	        'Created At'
		)->setComment(
            'Customer Egg Combination'
        );
        $setup->getConnection()->createTable($table);
        $setup->endSetup();
	}
}
