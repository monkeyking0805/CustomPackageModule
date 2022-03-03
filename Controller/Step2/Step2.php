<?php

namespace Oneclout\Custompackage\Controller\Step2;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Oneclout\Custompackage\Block\Product;
class Step2 extends \Magento\Framework\App\Action\Action
{   

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $_registry;
    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory resultPageFactory
     */

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Registry $registry
    )
    {
        $this->_registry = $registry;
        parent::__construct($context);
    }

    public function execute()
    {    
        //$this->_view->loadLayout();
        // $this->resultPageFactory->create();
        // $this->_view->getLayout()->initMessages();
        //$this->_view->renderLayout();
        // $this->categoryId();
        // $resultRedirect = $this->resultPageFactory ->create();          
        // $blockInstance = $resultRedirect->getLayout()->getBlock('step3.ajax');
        // $message['html'] = $blockInstance->toHtml();

        // /** Json Responce */
        // $this->getResponse()->representJson(
        //     $this->jsonData->jsonEncode($message)
        // );
        $categoryId =$_POST['categoryId'];
        $this->_registry->register('categoryId', $categoryId);
        $resultLayout = $this->resultFactory->create(ResultFactory::TYPE_LAYOUT);
        return $resultLayout; 

    }
}