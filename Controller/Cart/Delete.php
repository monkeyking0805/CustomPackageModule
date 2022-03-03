<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Oneclout\Custompackage\Controller\Cart;

class Delete extends \Magento\Checkout\Controller\Cart\Delete
{
    public function execute()
    {
        $packageProductIDs = $this->_checkoutSession->getPackageProductIDs();
        $items = $this->cart->getQuote()->getAllItems();
        
        foreach( $items as $item ) {
            if ( in_array( $item->getProductId(), $packageProductIDs ) ) {                
                try {
                    $this->cart->removeItem($item->getId());
                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage(__('We can\'t remove the item.'));
                    $this->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);
                }            
            } else {
                $allIsPackaged = false;
            }
        }
            
        $this->cart->saveQuote();
        $this->_checkoutSession->unsPackageProductIDs();        
        
        return;
    }

}
