<?php

namespace Razoyo\CarProfile\Setup;

use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    private CustomerSetupFactory $customerSetupFactory;

    public function __construct(
        CustomerSetupFactory $customerSetupFactory
    )
    {
        $this->customerSetupFactory = $customerSetupFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {

        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        $customerSetup->addAttribute(Customer::ENTITY, 'car_id', [
            'type' => 'varchar',
            'label' => 'Car ID',
            'input' => 'text',
            'required' => false,
            'visible' => true,
            'system' => false,
            'position' => 100,
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'car_id');
        $attribute->setData('used_in_forms', ['adminhtml_customer']);
        $attribute->save();

    }
}
