<?php

namespace Godogi\EasterEggHunt\Controller\EasterEgg;

use Magento\Framework\Json\Helper\Data as JsonHelper;

class Addegg extends \Magento\Framework\App\Action\Action {
    
    protected $_mediaDirectory;
    protected $_fileUploaderFactory;
    public $_storeManager;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        JsonHelper $jsonHelper,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->jsonHelper = $jsonHelper;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_storeManager = $storeManager;
    }

    
    public function execute(){
        $_postData = $this->getRequest()->getPost();
        $message = "";
        $newFileName = "";
        $error = false;
        $data = array();
        
        try{
            
            $target = $this->_mediaDirectory->getAbsolutePath('eastereggs/');

            $uploader = $this->_fileUploaderFactory->create(['fileId' => 'file']);
            $_fileType = $uploader->getFileExtension();
            
            $uniqueId = uniqid();
            $newFileName = $uniqueId.'.'.$_fileType;
            $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
            $uploader->setAllowRenameFiles(true);
            
            $result = $uploader->save($target, $newFileName); //Use this if you want to change your file name
            if ($result['file']) {
                $_mediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
                $_src = $_mediaUrl.'eastereggs/'.$newFileName;
                $error = false;
                $message = "File has been successfully uploaded";
                $html = '<div class="cntr"><div class="rmv">x</div><img src="'.$_src.'"><div class="code">[egg id="{id_egg}"]</div></div>';
                $data = array('filename' => $newFileName, 'path' => $_mediaUrl.'eastereggs/'.$newFileName, 'fileType' => $_fileType, 'html' => $html);
            }
            
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
        }
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData([
                    'message' => $message,
                    'data' => $data,
                    'error' => $error
        ]);
    }
}