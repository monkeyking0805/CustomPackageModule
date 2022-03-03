<?php

namespace Oneclout\Custompackage\Controller\Step7;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Data\Form\FormKey;
use Magento\Checkout\Model\Cart;
use Magento\Catalog\Model\Product;
class Step7 extends \Magento\Framework\App\Action\Action
{   

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_registry;
    protected $formKey;   
    protected $cart;
    protected $product;
    protected $checkoutSession;
    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory resultPageFactory
     */

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        FormKey $formKey,
        Cart $cart,
        Product $product,
        \Magento\Checkout\Model\Session $checkoutSession

    ) {
        $this->formKey = $formKey;
        $this->cart = $cart;
        $this->product = $product;  
        $this->_registry = $registry;
        $this->checkoutSession = $checkoutSession;
        parent::__construct($context);
    }

    public function execute()
    {      
        if(isset($_POST['productId'])){
            $productId=$_POST['productId'];
            $customPrice=$_POST['price'];
            $params = array(
                'form_key' => $this->formKey->getFormKey(),
                'product' => $productId, 
                'qty'   =>1
            );
            $product = $this->product->load($productId);   
            $this->cart->addProduct($product, $params);
            $productItem = $this->getProductQuote($product);
            $productItem->setCustomPrice($customPrice);
            $productItem->setOriginalCustomPrice($customPrice);
            $productItem->getProduct()->setIsSuperMode(true);
            $this->cart->save();
            
            $packageProductIDs = $this->checkoutSession->getPackageProductIDs();
            if ( empty($packageProductIDs) ) {
                $packageProductIDs = [intval($productId)];
            } else {
                $packageProductIDs[] = intval($productId);
            }
            $this->checkoutSession->setPackageProductIDs($packageProductIDs);
        }
        else{
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
    public function getProductQuote($product)
    {
        $quote = $this->checkoutSession->getQuote();
        $cartItems = $quote->getItemByProduct($product);
        return $cartItems;
    }
}