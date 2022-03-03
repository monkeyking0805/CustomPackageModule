define([
    "jquery",
    'Magento_Ui/js/modal/alert',
    "jquery/ui",
], function ($, alert) {
    'use strict';
    $.widget('mage.custompackage', {
        options: {},
        _create: function () {
            var self = this;
            alert({
                content: "Your Js working..."
            });
        }
    });
    return $.mage.custompackage;
});