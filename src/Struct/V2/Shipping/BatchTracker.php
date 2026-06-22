<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Shipping;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Shipping\Tracker;

#[OA\Schema(schema: 'paypal_v2_shipping_batch_tracker')]
class BatchTracker extends Struct
{
    #[OA\Property(type: 'string')]
    protected string $transactionId;

    #[OA\Property(type: 'string')]
    protected string $trackingNumber;

    #[OA\Property(type: 'string', default: Tracker::STATUS_SHIPPED)]
    protected string $status = Tracker::STATUS_SHIPPED;

    #[OA\Property(type: 'string')]
    protected string $carrier;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $carrierNameOther = null;

    #[OA\Property(type: 'boolean', default: false)]
    protected bool $notifyBuyer = false;

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function setTransactionId(string $transactionId): void
    {
        $this->transactionId = $transactionId;
    }

    public function getTrackingNumber(): string
    {
        return $this->trackingNumber;
    }

    public function setTrackingNumber(string $trackingNumber): void
    {
        $this->trackingNumber = $trackingNumber;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getCarrier(): string
    {
        return $this->carrier;
    }

    public function setCarrier(string $carrier): void
    {
        $this->carrier = $carrier;
    }

    public function getCarrierNameOther(): ?string
    {
        return $this->carrierNameOther;
    }

    public function setCarrierNameOther(?string $carrierNameOther): void
    {
        $this->carrierNameOther = $carrierNameOther;
    }

    public function isNotifyBuyer(): bool
    {
        return $this->notifyBuyer;
    }

    public function setNotifyBuyer(bool $notifyBuyer): void
    {
        $this->notifyBuyer = $notifyBuyer;
    }
}
