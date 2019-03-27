<?php

namespace Test\Status\Block\Form;

use \Test\Status\Setup\InstallData;

class Edit extends \Magento\Customer\Block\Account\Dashboard
{

    /**
     * Return the Url for saving.
     *
     * @return string
     */
    public function getSaveUrl()
    {
        return $this->_urlBuilder->getUrl(
            'customerstatus/edit/statusPost'
        );
    }

    /**
     * Get current status attribute value
     *
     * @return string|null
     */
    public function getCustomerStatus()
    {
        $customer = $this->customerRepository->getById($this->customerSession->getCustomerId());
        /** @var \Magento\Framework\Api\AttributeValue $customStatus */
        $customStatus = $customer->getCustomAttribute(InstallData::ATTRIBUTE_CODE);

        if (isset($customStatus)) {
            return $customStatus->getValue();
        }
        return null;
    }
}
