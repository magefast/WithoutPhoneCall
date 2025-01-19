<?php

namespace Dragonfly\WithoutPhoneCall\Observer;

use Exception;
use Magento\Checkout\Model\Session;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;

class SaveWithoutPhoneCallObserver implements ObserverInterface
{
    private Session $checkoutSession;

    /**
     * @param Session $checkoutSession
     */
    public function __construct(
        Session $checkoutSession
    )
    {
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @param Observer $observer
     * @return $this
     * @throws Exception
     */
    public function execute(Observer $observer)
    {
        if (!$this->checkoutSession->getWithoutPhoneCall()) {
            return $this;
        }

        $order = $observer->getEvent()->getOrder();

        $text = $this->checkoutSession->getWithoutPhoneCall();

        if (empty($text)) {
            return;
        }

        if (!empty($text)) {
            $text = trim($text);
        }

        $this->addWithoutPhoneCallToOrder($order, $text);

        $this->unsetSessionVar();
    }

    /**
     * @param $order
     * @param $value
     * @return void
     * @throws LocalizedException
     */
    public function addWithoutPhoneCallToOrder($order, $value): void
    {
        try {
            $order->setWithoutPhoneCall(boolval($value));
            $order->save();
        } catch (Exception $e) {
            throw new LocalizedException(__("Failed to add the without_phone_call to the order: %1", $e->getMessage()));
        }
    }

    private function unsetSessionVar()
    {
        $this->checkoutSession->unsWithoutPhoneCall();
    }
}
