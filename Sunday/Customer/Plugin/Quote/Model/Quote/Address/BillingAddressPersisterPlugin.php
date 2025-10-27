<?php
/**
 * @author      Anastasiia Miednykh <avmednykh@gmail.com>
 * @copyright   Copyright (c) 2025
 * @license     https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Sunday\Customer\Plugin\Quote\Model\Quote\Address;

use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Model\Quote\Address\BillingAddressPersister;
use Psr\Log\LoggerInterface;

class BillingAddressPersisterPlugin
{
    /**
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly LoggerInterface $logger
    ) {

    }

    /**
     * @param BillingAddressPersister $subject
     * @param $quote
     * @param AddressInterface $address
     * @param bool $useForShipping
     * @return void
     */
    public function beforeSave(
        BillingAddressPersister $subject,
        $quote,
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
