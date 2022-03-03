<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Oneclout\Custompackage\Rewrite\Magento\Checkout\Block\Cart\Item\Renderer\Actions;

use Magento\Framework\View\Element\Template;

/**
 * @api
 * @since 100.0.2
 */
class Edit extends \Magento\Checkout\Block\Cart\Item\Renderer\Actions\Edit
{
    protected $checkoutSession;

    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        Template\Context $context,
        array $data = []
    ) {
        $this->checkoutSession = $checkoutSession;
        parent::__construct($context, $data);
    }

    public function getCheckoutSession()
    {
        return $this->checkoutSession;
    }
}
