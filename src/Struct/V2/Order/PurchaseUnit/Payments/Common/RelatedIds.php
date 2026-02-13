<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments\Common;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

/**
 * Related IDs for a payment resource, provided in webhook supplementary data.
 *
 * @see https://developer.paypal.com/docs/api/payments/v2/
 */
#[OA\Schema(schema: 'paypal_v2_order_purchase_unit_payments_common_related_ids')]
class RelatedIds extends Struct
{
    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $orderId = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $authorizationId = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $captureId = null;

    public function getOrderId(): ?string
    {
        return $this->orderId;
    }

    public function setOrderId(?string $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getAuthorizationId(): ?string
    {
        return $this->authorizationId;
    }

    public function setAuthorizationId(?string $authorizationId): void
    {
        $this->authorizationId = $authorizationId;
    }

    public function getCaptureId(): ?string
    {
        return $this->captureId;
    }

    public function setCaptureId(?string $captureId): void
    {
        $this->captureId = $captureId;
    }
}
