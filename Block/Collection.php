<?php
namespace Oneclout\Custompackage\Block;

use Magento\Catalog\Block\Product;
use Magento\Catalog\Model\Category;

class Collection extends \Magento\Framework\View\Element\Template
{
    protected $_categoryCollectionFactory;
    protected $_productRepository;
    protected $_registry;
    protected $_categoryFactory;
    protected $_productCollectionFactory;
    protected $_configurableProduct;
    protected $_productVisibility;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,        
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableProduct,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        array $data = []
    ) {
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_productRepository = $productRepository;
        $this->_categoryFactory = $categoryFactory;
        $this->_registry = $registry;
        $this->_productCollectionFactory = $productCollectionFactory; 
        $this->_configurableProduct = $configurableProduct;
        $this->_productVisibility = $productVisibility;
        parent::__construct($context, $data);
    }

    public function getCategoryId() {
        return $this->_registry->registry('categoryId');
    }

    public function getPageNo() {
        return $this->_registry->registry('pageno');
    }

    public function getModel() {
        return $this->_registry->registry('model');
    }

    public function getConfigurableProductId() {
        return $this->_registry->registry('configurableproductId');
    }
    
    public function getCategory($categoryId) {   
        $cars = array();
        $category = $this->_categoryFactory->create()->load($categoryId);
        return $category;
    }
    public function getModelId() {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
        $resource = $objectManager->get("Magento\Framework\App\ResourceConnection");
        $connection = $resource->getConnection();
        $model=$this->getModel();
        $sql = "SELECT v1.option_id 
              FROM   eav_attribute ea 
                   INNER JOIN (SELECT DISTINCT eao.option_id, 
                                               eao.attribute_id, 
                                               eaov.value 
                               FROM   eav_attribute_option eao 
                                      INNER JOIN eav_attribute_option_value eaov 
                                              ON eaov.option_id = eao.option_id)AS v1 
                           ON ea.attribute_id = v1.attribute_id 
            WHERE  ea.attribute_code = 'package_models' 
                   AND v1.value = '".$model."'";
        $result=$connection->fetchAll($sql);
        if ( ! empty($result) ) {
            return $result[0]['option_id'];
        }
        return false;
    }
    
    public function getProductCollection() {  
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
        $items = array();
        $inststockitems = array();
        $categories = [$this->getCategoryId()];
        $collection = $this->_productCollectionFactory->create()->addAttributeToSort('price', 'ASC');
        $collection->addAttributeToSelect('*');
        $collection->addCategoriesFilter(['in' => $categories]);
        $collection->addAttributeToFilter('include_in_package', 1);      
        $custom_collection = array();
        //$collection->setVisibility($this->_productVisibility->getVisibleInSiteIds());  

        foreach ($collection as $_item) {
    
            $instock = $this->getStcokinfo()->getStockQty($_item->getId(), $_item->getStore()->getWebsiteId());
            if ( $instock > 0 && $_item->getTypeId() != 'configurable' ) {
                $parentConfigObject = $this->_configurableProduct->getParentIdsByChild($_item->getId());
                if ( $parentConfigObject ) {
                    continue;
                }
                $inststockitems[] = $_item;
            } elseif ( $_item->getTypeId() == 'configurable' ) {
                $allChildProducts = $_item->getTypeInstance()->getUsedProducts($_item);
                foreach ( $allChildProducts as $product ) {
                    $instock = $this->getStcokinfo()->getStockQty($product->getId(), $product->getStore()->getWebsiteId());
                    if ( $instock > 0 ) {
                        $inststockitems[] = $_item;
                        break;
                    }
                }
            }
        }

        $collection = $inststockitems;
        if ( null !== $this->getModel() ) {
            foreach ( $collection as $_item ) {
                $_packageModel = ( explode ( ",", $_item->getPackageModels() ) );

                if ( in_array( $this->getModelId(), $_packageModel, true ) ){
                    $items[] = $_item;
                }
            }
            $collection = $items;
        }
        foreach ($collection as $_item) {
            if ($_item->getTypeId() != 'configurable' && $_item->getPrice() == 0) {
                array_unshift($custom_collection, $_item);
            }else{
                array_push($custom_collection, $_item);
            }
        }
        return $custom_collection;
    }
    
    public function getPages() {
        $limit = 24;
        $totalProduct = count( $this->getProductCollection() );
        $numberofpage = (int) ( $totalProduct / $limit );
        $number = $totalProduct % $limit;

        if ( $number > 0 ) {
            $numberofpage++;
        }

        return $numberofpage;
    }

    public function getStcokinfo() {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $stockState = $objectManager->get('\Magento\CatalogInventory\Api\StockStateInterface');
        return $stockState;   
    }
}
