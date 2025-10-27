<?php

declare(strict_types=1);

namespace Sunday\Customer\Setup\Patch\Data;

use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class ProfessionalTitleAttribute implements DataPatchInterface
{
    const ATTRIBUTE_CODE = 'professional_title';
    public function __construct(
        private readonly CustomerSetupFactory     $customerSetupFactory,
        private readonly AttributeSetFactory      $attributeSetFactory,
        private readonly EavConfig                $eavConfig,
        private readonly ModuleDataSetupInterface $moduleDataSetup
    )
    {

    }

    /**
     * @return array|string[]
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @return array|string[]
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * @return void
     */
    public function apply()
    {

        $this->moduleDataSetup->getConnection()->startSetup();

        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $customerSetup->removeAttribute('customer', self::ATTRIBUTE_CODE);

        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();

        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

        $customerSetup->addAttribute('customer', self::ATTRIBUTE_CODE, [
            'label' => 'Professional title',
            'input' => 'text',
            'type' => 'varchar',
            'required' => false,
            'position' => 100,
            'visible' => true,
            'system' => false,
            'is_used_in_grid' => true,
            'is_visible_in_grid' => true,
            'is_filterable_in_grid' => true,
            'is_searchable_in_grid' => true,
            'visible_on_front' => true,
            'backend' => '',
            'comment' => ''
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', self::ATTRIBUTE_CODE)
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => [
                    'customer_account_create',
                    'customer_account_edit',
                    'adminhtml_customer'
                ]
            ]);

        $attribute->save();

        $this->moduleDataSetup->getConnection()->endSetup();
    }
}
