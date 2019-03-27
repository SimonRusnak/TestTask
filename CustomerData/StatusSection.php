<?php

namespace Test\Status\CustomerData;

use \Test\Status\Setup\InstallData;
use Magento\Customer\CustomerData\SectionSourceInterface;

class StatusSection implements SectionSourceInterface
{
    /** @var \Magento\Customer\Model\Session */
    protected $customerSession;

    /**
     * StatusSection constructor.
     * @param \Magento\Customer\Model\SessionFactory $customerSession
     */
    public function __construct(
        \Magento\Customer\Model\SessionFactory $customerSession
    ) {
        $this->customerSession = $customerSession->create();
    }

    /**
     * @return string|null
     */
    private function getCurrentCustomerStatus()
    {
        if ($this->customerSession->isLoggedIn() && $customer = $this->customerSession->getCustomer()) {
            $status = $customer->getData(InstallData::ATTRIBUTE_CODE);
            return $status;
        }
        return null;
    }


    /**
     * {@inheritdoc}
     */
    public function getSectionData()
    {
        if ($this->customerSession->isLoggedIn()) {
            return ['current_customer_status' => $this->getCurrentCustomerStatus()];
        }

        return [];
    }
}