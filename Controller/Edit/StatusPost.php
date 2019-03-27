<?php

namespace Test\Status\Controller\Edit;

use Test\Status\Setup\InstallData;

class StatusPost extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $session;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    private $formKeyValidator;

    /**
     * StatusPost constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
    ) {
        $this->formKeyValidator = $formKeyValidator;
        $this->session = $customerSession;
        $this->customerRepository = $customerRepository;
        parent::__construct($context);
    }

    /**
     * Save action exec
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $validFormKey = $this->formKeyValidator->validate($this->getRequest());

        if ($this->getRequest()->isPost() && $validFormKey) {
            $status = $this->getRequest()->getParam(InstallData::ATTRIBUTE_CODE);

            $customerId = $this->session->getCustomerId();
            if (isset($status) && isset($customerId)) {
                try {
                    $customer = $this->customerRepository->getById($customerId);
                    $customer->setCustomAttribute(InstallData::ATTRIBUTE_CODE, $status);
                    $this->customerRepository->save($customer);
                    $this->messageManager->addSuccessMessage(__("Status was successfully saved."));
                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage(__("Status has not been saved. Please, try again."));
                }
            }
        }
        return $resultRedirect->setPath('customerstatus/edit/index');
    }
}
