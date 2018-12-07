<?php
namespace Godogi\EasterEggHunt\Model\ResourceModel\Eggcus;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Godogi\EasterEggHunt\Model\Eggcus', 'Godogi\EasterEggHunt\Model\ResourceModel\Eggcus');
	}
}