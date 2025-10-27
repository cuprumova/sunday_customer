<?php
/**
 * @author      Anastasiia Miednykh <avmednykh@gmail.com>
 * @copyright   Copyright (c) 2025
 * @license     https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Sunday\Customer\Plugin\Checkout\Block\Checkout;

use Magento\Checkout\Block\Checkout\LayoutProcessor;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

class LayoutProcessorPlugin
{
    /**
     * @param Session $customerSession
     * @param CustomerRepositoryInterface $customerRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly Session $customerSession,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly LoggerInterface $logger
    )
    {
    }

    /**
     * @param LayoutProcessor $subject
     * @param array $result
     * @return array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function afterProcess(
        LayoutProcessor $subject,
        array           $result
    ): array
    {
        $attributeCode = 'professional_title';

        $configuration = $result['components']['checkout']['children']['steps']['children']['billing-step']['children']
        ['payment']['children']['payments-list']['children'];

        foreach ($configuration as $paymentGroup => $groupConfig) {
            if (isset($groupConfig['component']) && $groupConfig['component']
                === 'Magento_Checkout/js/view/billing-address') {
                $result['components']['checkout']['children']['steps']['children']['billing-step']['children']
                ['payment']['children']['payments-list']['children'][$paymentGroup]['children']['form-fields']
                ['children'][$attributeCode] = $this->getConfig('billingAddress', $attributeCode);;
            }
        }

        $result['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children'][$attributeCode] =
            $this->getConfig('shippingAddress', $attributeCode);

        return $result;

    }

    /**
     * @param string $addressType
     * @param string $attributeCode
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getConfig(string $addressType, string $attributeCode)
    {
        return [
            'component' => 'Magento_Ui/js/form/element/abstract',
            'config' => [
                'customScope' => $addressType.'.custom_attributes',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/input',
                'options' => [],
                'id' => $attributeCode
            ],
            'dataScope' => $addressType . '.custom_attributes.' . $attributeCode,
            'label' => 'Professional Title',
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => [],
            'sortOrder' => 1000,
            'id' => $attributeCode,
            'value' => $this->getDefaultValue()
        ];
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getDefaultValue()
    {
        try {
            $customerId = $this->customerSession->getCustomerId();
            $customer = $this->customerRepository->getById($customerId);

            if ($customer->getCustomAttribute('professional_title')) {
                return $customer->getCustomAttribute('professional_title')->getValue();
            }
        } catch (\Exception $exception) {
            $this->logger->critical($exception);
        }

        return '';
    }
}
