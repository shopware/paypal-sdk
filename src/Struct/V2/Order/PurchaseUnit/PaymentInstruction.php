<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\PaymentInstruction\PlatformFee;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\PaymentInstruction\PlatformFeeCollection;

#[OA\Schema(schema: 'paypal_v2_order_purchase_unit_payment_instruction')]
class PaymentInstruction extends Struct
{
    public const DISBURSEMENT_MODE_INSTANT = 'INSTANT';
    public const DISBURSEMENT_MODE_DELAYED = 'DELAYED';
    public const DISBURSEMENT_MODES = [
        self::DISBURSEMENT_MODE_INSTANT,
        self::DISBURSEMENT_MODE_DELAYED,
    ];

    public const MAX_LENGTH_PAYEE_PRICING_TIER_ID = 20;
    public const MAX_LENGTH_PAYEE_RECEIVABLE_FX_RATE_ID = 4000;

    #[OA\Property(type: 'array', items: new OA\Items(ref: PlatformFee::class))]
    protected PlatformFeeCollection $platformFees;

    /**
     * @phpstan-var self::DISBURSEMENT_MODE_INSTANT|self::DISBURSEMENT_MODE_DELAYED
     */
    #[OA\Property(type: 'string', enum: self::DISBURSEMENT_MODES)]
    protected string $disbursementMode;

    #[OA\Property(type: 'string', maxLength: self::MAX_LENGTH_PAYEE_PRICING_TIER_ID)]
    protected string $payeePricingTierId;

    #[OA\Property(type: 'string', maxLength: self::MAX_LENGTH_PAYEE_RECEIVABLE_FX_RATE_ID)]
    protected string $payeeReceivableFxRateId;

    public function getPlatformFees(): PlatformFeeCollection
    {
        return $this->platformFees;
    }

    public function setPlatformFees(PlatformFeeCollection $platformFees): void
    {
        $this->platformFees = $platformFees;
    }

    /**
     * @phpstan-return self::DISBURSEMENT_MODE_INSTANT|self::DISBURSEMENT_MODE_DELAYED
     */
    public function getDisbursementMode(): string
    {
        return $this->disbursementMode;
    }

    /**
     * @phpstan-param self::DISBURSEMENT_MODE_INSTANT|self::DISBURSEMENT_MODE_DELAYED $disbursementMode
     */
    public function setDisbursementMode(string $disbursementMode): void
    {
        $this->disbursementMode = $disbursementMode;
    }

    public function getPayeePricingTierId(): string
    {
        return $this->payeePricingTierId;
    }

    /**
     * @throws \LengthException if given parameter is too long
     */
    public function setPayeePricingTierId(string $payeePricingTierId): void
    {
        if (\mb_strlen($payeePricingTierId) > self::MAX_LENGTH_PAYEE_PRICING_TIER_ID) {
            throw new \LengthException(
                \sprintf(
                    '%s::$payeePricingTierId must not be longer than %s characters',
                    self::class,
                    self::MAX_LENGTH_PAYEE_PRICING_TIER_ID
                )
            );
        }

        $this->payeePricingTierId = $payeePricingTierId;
    }

    public function getPayeeReceivableFxRateId(): string
    {
        return $this->payeeReceivableFxRateId;
    }

    /**
     * @throws \LengthException if given parameter is too long
     */
    public function setPayeeReceivableFxRateId(string $payeeReceivableFxRateId): void
    {
        if (\mb_strlen($payeeReceivableFxRateId) > self::MAX_LENGTH_PAYEE_RECEIVABLE_FX_RATE_ID) {
            throw new \LengthException(
                \sprintf(
                    '%s::$payeeReceivableFxRateId must not be longer than %s characters',
                    self::class,
                    self::MAX_LENGTH_PAYEE_RECEIVABLE_FX_RATE_ID
                )
            );
        }

        $this->payeeReceivableFxRateId = $payeeReceivableFxRateId;
    }
}
