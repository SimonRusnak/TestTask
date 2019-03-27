<?php

namespace Test\Status\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Model\Customer;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    const ATTRIBUTE_CODE = 'status_test';

    /**
     * @var \Magento\Eav\Model\Entity\Attribute\SetFactory
     */
    private $attributeSetFactory;

    /**
     * @var \Magento\Customer\Setup\CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Attribute
     */
    private $attribute;


    public function __construct(
        \Magento\Customer\Model\ResourceModel\Attribute $attribute,
        \Magento\Eav\Model\Entity\Attribute\SetFactory $attributeSetFactory,
        \Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory
    ) {
        $this->attribute = $attribute;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->startSetup();
        /** @var \Magento\Customer\Setup\CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        $customerSetup->addAttribute(
            Customer::ENTITY,
            self::ATTRIBUTE_CODE,
            [
                'type' => 'varchar',
                'label' => 'Status',
                'input' => 'text',
                'unique' => false,
                'required' => false,
                'default' => null,
                'sort_order' => 200,
                'system' => false,
                'position' => 200,
                'visible' => true,
                'visible_on_front' => true,
            ]
        );

        $customerEntity = $customerSetup->getEavConfig()
            ->getEntityType(Customer::ENTITY);
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();
        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroup = $attributeSet->getDefaultGroupId($attributeSetId);

        $attribute = $customerSetup->getEavConfig()
            ->getAttribute(Customer::ENTITY, self::ATTRIBUTE_CODE)
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroup,
                'used_in_forms' => ['adminhtml_customer'],
            ]);
        $this->attribute->save($attribute);

        $setup->endSetup();
    }
}
