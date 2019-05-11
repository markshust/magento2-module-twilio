<?php
namespace MarkShust\Twilio\Observer;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\ScopeInterface;
use Psr\Log\LoggerInterface;
use Twilio\Rest\ClientFactory;

/**
 * Class SendOrderNotification
 * @package MarkShust\Twilio\Observer
 */
class SendOrderNotification implements ObserverInterface
{
    /** @var ClientFactory */
    private $clientFactory;

    /** @var LoggerInterface */
    private $logger;

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /** @var EncryptorInterface */
    private $encryptor;

    /**
     * SendOrderNotification constructor.
     * @param ClientFactory $clientFactory
     * @param LoggerInterface $logger
     * @param ScopeConfigInterface $scopeConfig
     * @param EncryptorInterface $encryptor
     */
    public function __construct(
        ClientFactory $clientFactory,
        LoggerInterface $logger,
        ScopeConfigInterface $scopeConfig,
        EncryptorInterface $encryptor
    ) {
        $this->clientFactory = $clientFactory;
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->encryptor = $encryptor;
    }

    /**
     * Send an order notification in response to an observer object containing an 'order' property.
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        if (!$this->getGeneralConfig('enabled')) {
            return;
        }

        $order = $observer->getData('order');
        $client = $this->clientFactory->create([
            'username' => $this->getGeneralConfig('account_sid'),
            'password' => $this->encryptor->decrypt($this->getGeneralConfig('auth_token')),
        ]);
        $params = [
            'from' => $this->getGeneralConfig('send_from_number'),
            'body' => $this->getBody($order),
        ];

        try {
            $client->messages->create($this->getGeneralConfig('send_to_number'), $params);
        } catch (\Exception $e) {
            $this->logger->critical('Error message', ['exception' => $e]);
        }
    }

    /**
     * Construct the message of the body to send from a given order object.
     *
     * @param $order
     * @return string
     */
    public function getBody($order)
    {
        $incrementId = $order->getData('increment_id');
        $shippingDescription = $order->getData('shipping_description');
        $result = "New order: #$incrementId" . PHP_EOL;
        $result .= PHP_EOL;
        $result .= "Shipping method: $shippingDescription" . PHP_EOL;
        $result .= PHP_EOL;

        foreach ($order->getData('items') as $item) {
            $qty = $item->getData('qty_ordered');
            $sku = $item->getData('sku');
            $name = $item->getData('name');
            $result .= "[$qty x $sku] $name" . PHP_EOL;
        }

        return $result;
    }

    /**
     * Get the scoped config value.
     *
     * @param $value
     * @return mixed
     */
    public function getGeneralConfig($value)
    {
        return $this->scopeConfig->getValue(
            "markshust_twilio/general/$value",
            ScopeInterface::SCOPE_STORE
        );
    }
}
