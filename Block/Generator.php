<?php
namespace Godogi\EasterEggHunt\Block;
class Generator extends \Magento\Framework\View\Element\Template
{
	protected $_customerSession;
	protected $_scopeConfig;
	protected $_assetRepo;
	protected $_formKey;
	protected $_eggcusCollectionFactory;

	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Magento\Customer\Model\Session $customerSession,
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
		\Magento\Framework\View\Asset\Repository $assetRepo,
		\Magento\Framework\Data\Form\FormKey $formKey,
		\Godogi\EasterEggHunt\Model\ResourceModel\Eggcus\CollectionFactory $eggcusCollectionFactory)
	{
		$this->_customerSession = $customerSession;
		$this->_scopeConfig = $scopeConfig;
		$this->_assetRepo = $assetRepo;
		$this->_formKey = $formKey;
		$this->_eggcusCollectionFactory = $eggcusCollectionFactory;
		parent::__construct($context);
	}
	public function getSavedEggs(){
		$savedEggs = [];
		$customerId = $this->_customerSession->getCustomer()->getId();
		$collectionCus = $this->_eggcusCollectionFactory->create();
		$collectionCus->addFieldToFilter('customer_id', array('eq' => $customerId));
		foreach ($collectionCus as $eggcus) {
			$savedEggs[]=$eggcus['egg_id'];
		}
		return implode(",",$savedEggs);
	}
	public function getCouponCode(){
		if($this->isCustomerLoggedIn()){
			return $this->getConfig("easteregg/general/discount_code_registred");
		}else{
			return $this->getConfig("easteregg/general/discount_code_guests");
		}
	}
	public function getRedirectUrl(){
		return $this->getConfig("easteregg/general/url");
	}
	public function getFoundEggsTitle(){
		return $this->getConfig("easteregg/general/found_title");
	}
	public function getFoundEggsMessage(){
		return $this->getConfig("easteregg/general/found_message");
	}
	public function getFoundAllEggsTitle(){
		return $this->getConfig("easteregg/general/all_found_title");
	}
	public function getFoundAllEggsMessage(){
		return $this->getConfig("easteregg/general/all_found_message");
	}
	public function isPublic(){
		return $this->getConfig("easteregg/general/public");
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
    public function getNotLoggedInUsersMessage(){
		return $this->getConfig("easteregg/general/not_logged_in_users_message");
    }
    public function getNotLoggedInUsersTitle(){
		return $this->getConfig("easteregg/general/not_logged_in_users_title");
    }
	public function getConfig($config) {
        return $this->_scopeConfig->getValue($config, "websites");
    }
    public function getImageUrl($imageName){
    	return $this->_assetRepo->getUrl("Godogi_EasterEggHunt::images/" . $imageName);
    }
    public function getFormKey()
    {
         return $this->_formKey->getFormKey();
    }
}