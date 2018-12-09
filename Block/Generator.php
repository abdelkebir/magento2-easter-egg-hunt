<?php
namespace Godogi\EasterEggHunt\Block;
class Generator extends \Magento\Framework\View\Element\Template
{
	protected $_customerSession;
	protected $_scopeConfig;
	protected $_assetRepo;

	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Magento\Customer\Model\Session $customerSession,
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
		\Magento\Framework\View\Asset\Repository $assetRepo)
	{
		$this->_customerSession = $customerSession;
		$this->_scopeConfig = $scopeConfig;
		$this->_assetRepo = $assetRepo;
		parent::__construct($context);
	}
	public function getTutoTxt(){
        	return 'This is our tuto to learn Magento 2 programming!';
   	}
   	public function isCustomerLoggedIn(){
	    return $this->_customerSession->isLoggedIn();
	}
	public function getEggs(){
		$imagesString = $this->getConfig("easteregg/general/images");
		$imagesArray = explode(",",$imagesString);
		return $imagesArray;
	}
	public function getWidth(){
		return $this->getConfig("easteregg/general/default_egg_width");
	}
	public function getHeight(){
		return $this->getConfig("easteregg/general/default_egg_height");
	}
	public function getDensity(){
		return $this->getConfig("easteregg/general/density");
    }
    public function getImages(){
		return $this->getConfig("easteregg/general/images");
    }
	public function getConfig($config) {
        return $this->_scopeConfig->getValue($config, "websites");
    }
    public function getImageUrl($imageName){
    	return $this->_assetRepo->getUrl("Godogi_EasterEggHunt::images/" . $imageName);
    }
    
}