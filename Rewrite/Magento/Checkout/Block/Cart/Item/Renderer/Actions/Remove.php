<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Oneclout\Custompackage\Rewrite\Magento\Checkout\Block\Cart\Item\Renderer\Actions;

use Magento\Checkout\Helper\Cart;
use Magento\Framework\View\Element\Template;

/**
 * @api
 * @since 100.0.2
 */
class Remove extends \Magento\Checkout\Block\Cart\Item\Renderer\Actions\Remove
{
    /**
     * @var Cart
     */
    protected $cartHelper;

    protected $checkoutSession;

    /**
     * @param Template\Context $context
     * @param Cart $cartHelper
     * @param array $data
     * @codeCoverageIgnore
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        Template\Context $context,
        Cart $cartHelper,
        array $data = []
    ) {
        $this->checkoutSession = $checkoutSession;
        parent::__construct($context, $cartHelper, $data);
    }

    public function getCheckoutSession()
    {
        return $this->checkoutSession;
    }

    public function getDeletePostJson()
    {
        
        return $this->cartHelper->getDeletePostJson($this->getItem());
    }
}
