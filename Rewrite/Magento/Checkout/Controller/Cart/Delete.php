<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Oneclout\Custompackage\Rewrite\Magento\Checkout\Controller\Cart;

class Delete extends \Magento\Checkout\Controller\Cart\Delete
{
    public function execute()
    {
        if (!$this->_formKeyValidator->validate($this->getRequest())) {
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        $id = (int)$this->getRequest()->getParam('id');
        if ($id) {
            $packageProductIDs = $this->_checkoutSession->getPackageProductIDs();
            $productId = $this->cart->getQuote()->getItemById($id)->getProductId();
            if ( ( ! empty( $packageProductIDs ) ) && in_array( $productId, $packageProductIDs ) ) {
                $items = $this->cart->getQuote()->getAllItems();
                $allIsPackaged = true;
                foreach( $items as $item ) {
                    if ( in_array( $item->getProductId(), $packageProductIDs ) ) {
                        if ( $item->getProductId() != $productId ) {
                            try {
                                $this->cart->removeItem($item->getId());
                            } catch (\Exception $e) {
                                $this->messageManager->addErrorMessage(__('We can\'t remove the item.'));
                                $this->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);
                            }
                        }
                    } else {
                        $allIsPackaged = false;
                    }
                }
                
                try {
                    $this->cart->removeItem($id);
                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage(__('We can\'t remove the item.'));
                    $this->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);
                }
            
                $this->cart->saveQuote();
                $this->_checkoutSession->unsPackageProductIDs();
            } else {
                try {
                    $this->cart->removeItem($id)->save();
                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage(__('We can\'t remove the item.'));
                    $this->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);
                }
            }
        }
        $defaultUrl = $this->_objectManager->create(\Magento\Framework\UrlInterface::class)->getUrl('*/*');      

        return $this->resultRedirectFactory->create()->setUrl($this->_redirect->getRedirectUrl($defaultUrl));
    }

}
