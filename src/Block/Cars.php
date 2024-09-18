<?php

namespace Razoyo\CarProfile\Block;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Element\Template;

class Cars extends Template
{
    private CustomerSession $customerSession;

    public function __construct(
        Template\Context $context,
        CustomerSession $customerSession,
        \Razoyo\CarProfile\Service\Cars $carService,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->carService = $carService;
        $this->customerSession = $customerSession;
    }

    public function getCustomerId(): ?int
    {
        return $this->customerSession->isLoggedIn() ? $this->customerSession->getCustomerId() : null;
    }

    public function getCarId(): ?string
    {
        // Assuming car_id is a custom attribute
        $customer = $this->customerSession->getCustomer();
        return $customer->getCarId() == '' ? '' : $customer->getCarId();
    }

    public function getMyToken(): ?string
    {
        return $this->customerSession->getData('your_token');
    }

    public function getCars(): array
    {
        return $this->carService->getCars()['cars'];
    }

    public function getCarDetails(): array
    {
        return $this->carService->getCarDetails($this->getCarId());
    }

}
