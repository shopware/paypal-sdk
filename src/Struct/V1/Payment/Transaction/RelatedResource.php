<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Payment\Transaction;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\ConstantsV1;
use Shopware\PayPalSDK\Struct\V1\Payment\Transaction\RelatedResource\Authorization;
use Shopware\PayPalSDK\Struct\V1\Payment\Transaction\RelatedResource\Capture;
use Shopware\PayPalSDK\Struct\V1\Payment\Transaction\RelatedResource\Order;
use Shopware\PayPalSDK\Struct\V1\Payment\Transaction\RelatedResource\Refund;
use Shopware\PayPalSDK\Struct\V1\Payment\Transaction\RelatedResource\Sale;

#[OA\Schema(schema: 'paypal_v1_payment_transaction_related_resource')]
class RelatedResource extends Struct
{
    public const SALE = ConstantsV1::INTENT_SALE;
    public const AUTHORIZE = ConstantsV1::INTENT_AUTHORIZE;
    public const ORDER = ConstantsV1::INTENT_ORDER;
    public const REFUND = 'refund';
    public const CAPTURE = 'capture';

    #[OA\Property(ref: Sale::class, nullable: true)]
    protected ?Sale $sale = null;

    #[OA\Property(ref: Authorization::class, nullable: true)]
    protected ?Authorization $authorization = null;

    #[OA\Property(ref: Order::class, nullable: true)]
    protected ?Order $order = null;

    #[OA\Property(ref: Refund::class, nullable: true)]
    protected ?Refund $refund = null;

    #[OA\Property(ref: Capture::class, nullable: true)]
    protected ?Capture $capture = null;

    public function getSale(): ?Sale
    {
        return $this->sale;
    }

    public function setSale(?Sale $sale): void
    {
        $this->sale = $sale;
    }

    public function getAuthorization(): ?Authorization
    {
        return $this->authorization;
    }

    public function setAuthorization(?Authorization $authorization): void
    {
        $this->authorization = $authorization;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): void
    {
        $this->order = $order;
    }

    public function getRefund(): ?Refund
    {
        return $this->refund;
    }

    public function setRefund(?Refund $refund): void
    {
        $this->refund = $refund;
    }

    public function getCapture(): ?Capture
    {
        return $this->capture;
    }

    public function setCapture(?Capture $capture): void
    {
        $this->capture = $capture;
    }
}
