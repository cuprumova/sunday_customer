<?php
/**
 * @author      Anastasiia Miednykh <avmednykh@gmail.com>
 * @copyright   Copyright (c) 2025
 * @license     https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
declare(strict_types=1);

namespace Sunday\Customer\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class SaveProfessionalTitleToOrder implements ObserverInterface
{
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $quote = $observer->getQuote();
        $order = $observer->getOrder();

        if ($quote->getBillingAddress()) {
            $order->getBillingAddress()->setProfessionalTitle(
                $quote->getBillingAddress()->getExtensionAttributes()->getProfessionalTitle()
            );
        }

        if ($quote->getShippingAddress()) {
            $order->getShippingAddress()->setProfessionalTitle(
                $quote->getShippingAddress()->getExtensionAttributes()->getProfessionalTitle()
            );
        }

    }
}
