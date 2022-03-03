<?php
 
namespace Oneclout\Custompackage\Controller\Index;
use Magento\Framework\Controller\ResultFactory;
use Oneclout\Custompackage\Block\Collection;
use Magento\Framework\App\Action\Action;
class Index extends \Magento\Framework\App\Action\Action
{
   protected $resultPageFactory;
   protected $checkoutSession;
   protected $cartRepository;
   protected $productFactory;
   protected $_pageFactory;
   protected $_registry;
   protected $productCollection;
   protected $collection;
  
   public function __construct(
       \Magento\Framework\App\Action\Context $context,
       \Magento\Framework\View\Result\PageFactory $resultPageFactory,
       \Magento\Checkout\Model\Session $checkoutSession,
       \Magento\Quote\Api\CartRepositoryInterface $cartRepository,
       \Magento\Catalog\Model\ProductFactory $productFactory,
       \Magento\Framework\View\Result\PageFactory $pageFactory,
       \Magento\Framework\Registry $registry,
       \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
       \Oneclout\Custompackage\Block\Collection $collection
   ) {
       $this->resultPageFactory = $resultPageFactory;
       $this->checkoutSession = $checkoutSession;
       $this->cartRepository = $cartRepository;
       $this->productFactory = $productFactory;
       $this->_registry = $registry;
       $this->productCollection = $collectionFactory->create();
       $this->collection = $collection;

       parent::__construct($context);
   }
   public function execute()
   {
    $this->_view->loadLayout();
    $this->resultPageFactory->create();
    $this->_view->getLayout()->initMessages();
    $this->_view->renderLayout();
  }
}