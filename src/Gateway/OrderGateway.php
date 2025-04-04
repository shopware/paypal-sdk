<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Gateway;

use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;
use Shopware\PayPalSDK\Contract\Gateway\OrderGatewayInterface;
use Shopware\PayPalSDK\Struct\V2\Order;
use Shopware\PayPalSDK\Struct\V2\Order\Tracker;
use Shopware\PayPalSDK\Struct\V2\Patch;
use Shopware\PayPalSDK\Struct\V2\PatchCollection;

class OrderGateway extends AbstractGateway implements OrderGatewayInterface
{
    public function createOrder(Order $order, ApiContextInterface $context): Order
    {
        return $this->request(
            'POST',
            self::GATEWAY_URL,
            $order,
            Order::class,
            $context
        );
    }

    public function getOrder(string $orderId, ApiContextInterface $context): Order
    {
        return $this->request(
            'GET',
            self::GATEWAY_URL . '/' . $orderId,
            null,
            Order::class,
            $context
        );
    }

    public function authorizeOrder(string $orderId, ApiContextInterface $context): Order
    {
        return $this->request(
            'POST',
            self::GATEWAY_URL . '/' . $orderId . '/authorize',
            null,
            Order::class,
            $context
        );
    }

    public function captureOrder(string $orderId, ApiContextInterface $context): Order
    {
        return $this->request(
            'POST',
            self::GATEWAY_URL . '/' . $orderId . '/capture',
            null,
            Order::class,
            $context
        );
    }

    public function patchOrder(string $orderId, PatchCollection $patches, ApiContextInterface $context): void
    {
        $this->request(
            'PATCH',
            self::GATEWAY_URL . '/' . $orderId,
            $patches,
            null,
            $context
        );
    }

    public function addTracker(Tracker $tracker, string $orderId, ApiContextInterface $context): Order
    {
        return $this->request(
            'POST',
            self::GATEWAY_URL . '/' . $orderId . '/track',
            $tracker,
            Order::class,
            $context,
        );
    }

    public function removeTracker(Tracker $tracker, string $orderId, ApiContextInterface $context): void
    {
        $this->request(
            'PATCH',
            self::GATEWAY_URL . '/' . $orderId . '/trackers/' . $tracker->getCaptureId() . '-' . $tracker->getTrackingNumber(),
            PatchCollection::createFromAssociative([[
                'op' => Patch::OPERATION_REPLACE,
                'path' => '/status',
                'value' => Order\PurchaseUnit\Shipping\Tracker::STATUS_CANCELLED,
            ]]),
            Order::class,
            $context,
        );
    }
}
