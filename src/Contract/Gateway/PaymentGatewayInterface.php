<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Contract\Gateway;

use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments\Authorization;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments\Capture;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments\Refund;

interface PaymentGatewayInterface
{
    public const GATEWAY_URL = '/v2/payments';

    public function getCapture(string $captureId, ApiContextInterface $context): Capture;

    public function getAuthorization(string $authorizationId, ApiContextInterface $context): Authorization;

    public function getRefund(string $refundId, ApiContextInterface $context): Refund;

    public function refundCapture(string $captureId, Refund $refund, ApiContextInterface $context): Refund;

    public function captureAuthorization(string $authorizationId, Capture $capture, ApiContextInterface $context): Capture;

    public function voidAuthorization(string $authorizationId, ApiContextInterface $context): void;
}
