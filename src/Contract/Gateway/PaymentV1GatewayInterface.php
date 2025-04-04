<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Contract\Gateway;

use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;
use Shopware\PayPalSDK\Struct\V1\Capture;
use Shopware\PayPalSDK\Struct\V1\Payment;
use Shopware\PayPalSDK\Struct\V1\Payment\Transaction\RelatedResource\Authorization;
use Shopware\PayPalSDK\Struct\V1\Payment\Transaction\RelatedResource\Order;
use Shopware\PayPalSDK\Struct\V1\Payment\Transaction\RelatedResource\Sale;

interface PaymentV1GatewayInterface
{
    public const GATEWAY_URL = '/v1/payments';

    public function getAuthorization(string $authorizationId, ApiContextInterface $context): Authorization;

    public function getCapture(string $captureId, ApiContextInterface $context): Capture;

    public function getOrder(string $orderId, ApiContextInterface $context): Order;

    public function getPayment(string $paymentId, ApiContextInterface $context): Payment;

    public function getSale(string $saleId, ApiContextInterface $context): Sale;
}
