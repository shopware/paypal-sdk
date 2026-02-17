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
 * Supplementary data for a payment resource, typically provided in webhook payloads.
 *
 * Contains related IDs that link a payment resource (capture, refund, authorization)
 * back to its parent order.
 *
 * @see https://developer.paypal.com/docs/api/payments/v2/
 */
#[OA\Schema(schema: 'paypal_v2_order_purchase_unit_payments_common_payment_supplementary_data')]
class PaymentSupplementaryData extends Struct
{
    #[OA\Property(ref: RelatedIds::class, nullable: true)]
    protected ?RelatedIds $relatedIds = null;

    public function getRelatedIds(): ?RelatedIds
    {
        return $this->relatedIds;
    }

    public function setRelatedIds(?RelatedIds $relatedIds): void
    {
        $this->relatedIds = $relatedIds;
    }
}
