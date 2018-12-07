<?php
namespace Godogi\EasterEggHunt\Model\ResourceModel;
class Eggcus extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	public function __construct(
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	){
		parent::__construct($context);
	}
	protected function _construct()
	{
		$this->_init('godogi_easteregghunt_eggcus', 'egg_cus_id');
	}
}