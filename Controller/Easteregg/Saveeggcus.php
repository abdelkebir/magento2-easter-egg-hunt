<?php

namespace Godogi\EasterEggHunt\Controller\EasterEgg;

class Saveeggcus extends \Magento\Framework\App\Action\Action {
    
    protected $_resultJsonFactory;
    protected $_formKeyValidator;
    protected $_customerSession;
    protected $_eggcusModel;
    protected $_eggcusCollectionFactory;
    protected $_scopeConfig;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Customer\Model\Session $customerSession,
        \Godogi\EasterEggHunt\Model\Eggcus  $eggcusModel,
        \Godogi\EasterEggHunt\Model\ResourceModel\Eggcus\CollectionFactory $eggcusCollectionFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_formKeyValidator = $formKeyValidator;
        $this->_customerSession = $customerSession;
        $this->_eggcusModel = $eggcusModel;
        $this->_eggcusCollectionFactory = $eggcusCollectionFactory;
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context);
    }


    public function execute(){
    	$error = 0;
    	$foundEggs = [];
    	$numberEggs = 0;
        try{
            if($this->_formKeyValidator->validate($this->getRequest())){
            	$eggId = $this->getRequest()->getParam('egg_id');
            	
            	$message = 'valid request';
            	$error = false;
            	$eggsList = $this->_scopeConfig->getValue("easteregg/general/images", "websites");
            	$eggsListArray = explode(",",$eggsList);
            	$numberEggs = count($eggsListArray);
            	$collectionCusEgg = $this->_eggcusCollectionFactory->create();
                if($this->_customerSession->isLoggedIn()){
                    $customerId = $this->_customerSession->getCustomer()->getId();
                    $collectionCusEgg->addFieldToFilter('customer_id', array('eq' => $customerId));
                }else{
                    $sessionId = $this->_customerSession->getSessionId();
                    $collectionCusEgg->addFieldToFilter('session_id', array('eq' => $sessionId));
                }
				
				$collectionCusEgg->addFieldToFilter('egg_id', array('eq' => $eggId));
				if($collectionCusEgg->getSize() == 0){
					$eggcus = $this->_eggcusModel;
                    if($this->_customerSession->isLoggedIn()){
                        $eggcus->setCustomerId($customerId);
                    }else{
                        $eggcus->setSessionId($sessionId);
                    }
					$eggcus->setEggId($eggId);
			        if($eggcus->save()){
			        	$collectionCus = $this->_eggcusCollectionFactory->create();
                        if($this->_customerSession->isLoggedIn()){
                            $collectionCus->addFieldToFilter('customer_id', array('eq' => $customerId));
                        }else{
                            $collectionCus->addFieldToFilter('session_id', array('eq' => $sessionId));
                        }
						foreach ($collectionCus as $eggcus) {
							$foundEggs[$eggcus['egg_id']]=$eggsListArray[$eggcus['egg_id']];
						}
			        	$error = 0;
			        	$message = 'Eggcus saved';
			        }else{
			        	$error = 2;
			        	$message = 'Eggcus not saved';
			        }
				}else{
					$error = 3;
			        $message = 'Eggcus already saved';
				}
            }else{
            	$message = 'Invalid request';
            	$error = 1;
            }
        } catch (\Exception $e) {
            $error = 1;
            $message = $e->getMessage();
        }

        $resultJson = $this->_resultJsonFactory->create();

        return $resultJson->setData([
                    'message' => $message,
                    'numberFoundEggs' => count($foundEggs),
                    'error' => $error
        ]);
    }
}