define([
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function (wrapper, quote) {
    'use strict';

    return function (setBillingAddressAction) {

        /**
         * @param {Object} address
         */
        return wrapper.wrap(setBillingAddressAction, function (originalAction) {

            var billingAddress = quote.billingAddress();
            if (billingAddress) {
                var attributeCode = 'professional_title';
                var professionalTitleValue = jQuery('[name="custom_attributes['+attributeCode+']"]').val();

                if (billingAddress.extension_attributes === undefined || billingAddress.extensionAttributes === null) {
                    billingAddress.extension_attributes = {};
                }


                billingAddress.extension_attributes[attributeCode] = professionalTitleValue;
                /*quote.billingAddress(billingAddress);*/
            }
            return originalAction();
        });
    };
});

