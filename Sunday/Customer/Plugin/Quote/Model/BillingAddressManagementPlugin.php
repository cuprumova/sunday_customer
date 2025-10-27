<?php
/**
 * @author      Anastasiia Miednykh <avmednykh@gmail.com>
 * @copyright   Copyright (c) 2025
 * @license     https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Sunday\Customer\Plugin\Quote\Model;

use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Model\BillingAddressManagement;
use Psr\Log\LoggerInterface;

class BillingAddressManagementPlugin
{
    /**
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @param BillingAddressManagement $subject
     * @param int $cartId
     * @param AddressInterface $address
     * @param $useForShipping
     * @return void
     */
    public function beforeAssign(
        BillingAddressManagement $subject,
        int $cartId,
        AddressInterface $address,
        $useForShipping = false
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
