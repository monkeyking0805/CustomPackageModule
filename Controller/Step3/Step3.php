<?php

namespace Oneclout\Custompackage\Controller\Step3;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Oneclout\Custompackage\Block\Product;
class Step3 extends \Magento\Framework\App\Action\Action
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
        $categoryId =$_POST['categoryId'];
        $this->_registry->register('categoryId', $categoryId);
        $categoryId =$_POST['pageno'];
        $this->_registry->register('pageno', $categoryId);
        $resultLayout = $this->resultFactory->create(ResultFactory::TYPE_LAYOUT);
        return $resultLayout; 

    }
}