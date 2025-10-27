define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper,quote) {
    'use strict';

    return function (setShippingInformationAction) {
        return wrapper.wrap(setShippingInformationAction, function (originalAction, addressData) {

            if (addressData.extension_attributes === undefined || addressData.extensionAttributes === null) {
                addressData.extension_attributes = {};
            }
            if (addressData.custom_attributes != undefined) {
                $.each(addressData.custom_attributes , function( key, value ) {
                    addressData['extension_attributes'][key] = value;
                });
            }

            return originalAction(addressData);
        });
    };
});
