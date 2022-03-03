<?php

namespace Oneclout\Custompackage\Controller\Step5;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;

class Step5 extends \Magento\Framework\App\Action\Action
{   

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
      protected $_registry;
    /**
     * @var \Magento\Framework\Json\Helper\Data
     */


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
        $categoryId =$_POST['categoryId'];
        $this->_registry->register('categoryId', $categoryId);
        $categoryId =$_POST['pageno'];
        $this->_registry->register('pageno', $categoryId);
        $modelname =$_POST['model'];
        $this->_registry->register('model', $modelname);
        $resultLayout = $this->resultFactory->create(ResultFactory::TYPE_LAYOUT);
        return $resultLayout;
    
    }
}