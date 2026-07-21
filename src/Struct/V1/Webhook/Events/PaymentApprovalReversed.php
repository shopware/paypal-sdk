<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Webhook\Events;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnitCollection;

/**
 * Resource shape for {@see \Shopware\PayPalSDK\Struct\V1\Webhook\WebhookEventTypes::CHECKOUT_PAYMENT_APPROVAL_REVERSED}.
 *
 * PayPal sends `order_id` (not `id`) on this event and often omits `resource_type`,
 * so {@see \Shopware\PayPalSDK\Struct\V1\Webhook\Event::assign()} maps by event type.
 */
#[OA\Schema(schema: 'paypal_v1_webhook_events_payment_approval_reversed')]
class PaymentApprovalReversed extends Struct
{
    #[OA\Property(type: 'string')]
    protected string $orderId;

    #[OA\Property(type: 'array', items: new OA\Items(ref: PurchaseUnit::class), nullable: true)]
    protected ?PurchaseUnitCollection $purchaseUnits = null;

    #[OA\Property(ref: PaymentSource::class, nullable: true)]
    protected ?PaymentSource $paymentSource = null;

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function setOrderId(string $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getPurchaseUnits(): ?PurchaseUnitCollection
    {
        return $this->purchaseUnits;
    }

    public function setPurchaseUnits(?PurchaseUnitCollection $purchaseUnits): void
    {
        $this->purchaseUnits = $purchaseUnits;
    }

    public function getPaymentSource(): ?PaymentSource
    {
        return $this->paymentSource;
    }

    public function setPaymentSource(?PaymentSource $paymentSource): void
    {
        $this->paymentSource = $paymentSource;
    }
}
