<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Gateway;

use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;
use Shopware\PayPalSDK\Contract\Gateway\PaymentV1GatewayInterface;
use Shopware\PayPalSDK\Struct\V1\Capture;
use Shopware\PayPalSDK\Struct\V1\Payment;
use Shopware\PayPalSDK\Struct\V1\Payment\Transaction\RelatedResource\Authorization;
use Shopware\PayPalSDK\Struct\V1\Payment\Transaction\RelatedResource\Order;
use Shopware\PayPalSDK\Struct\V1\Payment\Transaction\RelatedResource\Sale;

class PaymentV1Gateway extends AbstractGateway implements PaymentV1GatewayInterface
{
    public function getAuthorization(string $authorizationId, ApiContextInterface $context): Authorization
    {
        return $this->request(
            'GET',
            self::GATEWAY_URL . '/authorization/' . $authorizationId,
            null,
            Authorization::class,
            $context
        );
    }

    public function getCapture(string $captureId, ApiContextInterface $context): Capture
    {
        return $this->request(
            'GET',
            self::GATEWAY_URL . '/capture/' . $captureId,
            null,
            Capture::class,
            $context
        );
    }

    public function getOrder(string $orderId, ApiContextInterface $context): Order
    {
        return $this->request(
            'GET',
            self::GATEWAY_URL . '/orders/' . $orderId,
            null,
            Order::class,
            $context
        );
    }

    public function getPayment(string $paymentId, ApiContextInterface $context): Payment
    {
        return $this->request(
            'GET',
            self::GATEWAY_URL . '/payment/' . $paymentId,
            null,
            Payment::class,
            $context
        );
    }

    public function getSale(string $saleId, ApiContextInterface $context): Sale
    {
        return $this->request(
            'GET',
            self::GATEWAY_URL . '/sale/' . $saleId,
            null,
            Sale::class,
            $context
        );
    }
}
