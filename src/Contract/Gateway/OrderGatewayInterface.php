<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Contract\Gateway;

use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;
use Shopware\PayPalSDK\Struct\V2\Order;
use Shopware\PayPalSDK\Struct\V2\Order\Tracker;
use Shopware\PayPalSDK\Struct\V2\PatchCollection;

interface OrderGatewayInterface
{
    public const GATEWAY_URL = '/v2/checkout/orders';

    public function createOrder(Order $order, ApiContextInterface $context): Order;

    public function getOrder(string $orderId, ApiContextInterface $context): Order;

    public function authorizeOrder(string $orderId, ApiContextInterface $context): Order;

    public function captureOrder(string $orderId, ApiContextInterface $context): Order;

    public function patchOrder(string $orderId, PatchCollection $patches, ApiContextInterface $context): void;

    public function addTracker(Tracker $tracker, string $orderId, ApiContextInterface $context): Order;

    public function removeTracker(Tracker $tracker, string $orderId, ApiContextInterface $context): void;
}
