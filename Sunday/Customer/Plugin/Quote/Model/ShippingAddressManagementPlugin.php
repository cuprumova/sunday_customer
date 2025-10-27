<?php
/**
 * @author      Anastasiia Miednykh <avmednykh@gmail.com>
 * @copyright   Copyright (c) 2025
 * @license     https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Sunday\Customer\Plugin\Quote\Model;

use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Model\ShippingAddressManagement;
use Psr\Log\LoggerInterface;

class ShippingAddressManagementPlugin
{
    /**
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }
    /**
     * @param ShippingAddressManagement $subject
     * @param int $cartId
     * @param AddressInterface $address
     * @return void
     */
    public function beforeAssign(
        ShippingAddressManagement $subject,
        int $cartId,
        AddressInterface $address
    ) {
        $extensionAttributes = $address->getExtensionAttributes();
        if (!empty($extensionAttributes)) {
            try {
                $address->setProfessionalTitle($extensionAttributes->getProfessionalTitle());
            } catch (\Exception $exception) {
                $this->logger->critical($exception->getMessage());
            }
        }
    }
}
