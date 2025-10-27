define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper, quote) {
    'use strict';

    return function (setShippingInformationAction) {

        return wrapper.wrap(setShippingInformationAction, function (originalAction) {
            var shippingAddress = quote.shippingAddress();

            if (shippingAddress['extension_attributes'] === undefined) {
                shippingAddress['extension_attributes'] = {};
            }

            var attributeCode = 'professional_title';
            var professionalTitleValue = jQuery('[name="custom_attributes['+attributeCode+']"]').val();

            shippingAddress['extension_attributes'][attributeCode] = professionalTitleValue;
            //quote.shippingAddress(shippingAddress);
            return originalAction();
        });
    };
});
