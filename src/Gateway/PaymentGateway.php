<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Gateway;

use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;
use Shopware\PayPalSDK\Contract\Gateway\PaymentGatewayInterface;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments\Authorization;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments\Capture;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments\Refund;

class PaymentGateway extends AbstractGateway implements PaymentGatewayInterface
{
    public function getCapture(string $captureId, ApiContextInterface $context): Capture
    {
        return $this->request(
            'GET',
            self::GATEWAY_URL . '/captures/' . $captureId,
            null,
            Capture::class,
            $context
        );
    }

    public function getAuthorization(string $authorizationId, ApiContextInterface $context): Authorization
    {
        return $this->request(
            'GET',
            self::GATEWAY_URL . '/authorizations/' . $authorizationId,
            null,
            Authorization::class,
            $context
        );
    }

    public function getRefund(string $refundId, ApiContextInterface $context): Refund
    {
        return $this->request(
            'GET',
            self::GATEWAY_URL . '/refunds/' . $refundId,
            null,
            Refund::class,
            $context
        );
    }

    public function refundCapture(string $captureId, Refund $refund, ApiContextInterface $context): Refund
    {
        return $this->request(
            'POST',
            self::GATEWAY_URL . '/captures/' . $captureId . '/refund',
            $refund,
            Refund::class,
            $context
        );
    }

    public function captureAuthorization(string $authorizationId, Capture $capture, ApiContextInterface $context): Capture
    {
        return $this->request(
            'POST',
            self::GATEWAY_URL . '/authorizations/' . $authorizationId . '/capture',
            $capture,
            Capture::class,
            $context
        );
    }

    public function voidAuthorization(string $authorizationId, ApiContextInterface $context): void
    {
        $this->request(
            'POST',
            self::GATEWAY_URL . '/authorizations/' . $authorizationId . '/void',
            null,
            null,
            $context
        );
    }
}
