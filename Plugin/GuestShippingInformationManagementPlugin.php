<?php

declare(strict_types=1);

namespace Dragonfly\WithoutPhoneCall\Plugin;

use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Model\Session;
use Magento\Checkout\Model\ShippingInformationManagement;

class GuestShippingInformationManagementPlugin
{
    /**
     * @var Session
     */
    private Session $session;

    /**
     * Initialize dependencies
     *
     * @param Session $session
     */
    public function __construct(
        Session $session
    )
    {
        $this->session = $session;
    }

    /**
     * @param ShippingInformationManagement $subject
     * @param $cartId
     * @param ShippingInformationInterface $addressInformation
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSaveAddressInformation(
        ShippingInformationManagement $subject,
                                      $cartId,
        ShippingInformationInterface  $addressInformation
    ): void
    {
        $withoutPhoneCall = $addressInformation->getExtensionAttributes()->getWithoutPhoneCall();
        $this->session->setWithoutPhoneCall(boolval($withoutPhoneCall));
    }
}
