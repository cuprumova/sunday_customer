var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/set-billing-address': {
                'Sunday_Customer/js/action/set-billing-address-mixin': true
            },
            'Magento_Checkout/js/action/set-shipping-information': {
                'Sunday_Customer/js/action/set-shipping-information-mixin': true
            },
            'Magento_Checkout/js/action/place-order': {
                'Sunday_Customer/js/action/set-billing-address-mixin': true
            },
            'Magento_Checkout/js/action/create-shipping-address': {
                'Sunday_Customer/js/action/create-shipping-address-mixin': true
            },
        }
    }
};
