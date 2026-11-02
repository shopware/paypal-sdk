<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Struct\V2;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\PaymentInstruction;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\PaymentInstruction\PlatformFee;

/**
 * @internal
 */
#[CoversClass(PurchaseUnit::class)]
class PurchaseUnitTest extends TestCase
{
    public function testMissingResponseFieldsAreReadable(): void
    {
        $unit = new PurchaseUnit();

        static::assertNull($unit->getReferenceId());
        static::assertNull($unit->getAmount());
        static::assertNull($unit->getPayee());
        static::assertNull($unit->getPaymentInstruction());
        static::assertNull($unit->getDescription());
        static::assertNull($unit->getSupplementaryData());
        static::assertSame([], $unit->jsonSerialize());
    }

    public function testExplicitNullClearsValueWhileMissingValueIsRetained(): void
    {
        $unit = (new PurchaseUnit())->assign(['custom_id' => 'old', 'invoice_id' => 'keep']);
        $unit->assign(['custom_id' => null]);

        static::assertNull($unit->getCustomId());
        static::assertSame('keep', $unit->getInvoiceId());
        static::assertSame(['custom_id' => null, 'invoice_id' => 'keep'], $unit->jsonSerialize());
    }

    public function testPaymentInstructionAssignmentAndSerialization(): void
    {
        $purchaseUnit = (new PurchaseUnit())->assign([
            'reference_id' => 'default',
            'amount' => [
                'currency_code' => 'USD',
                'value' => '10.00',
            ],
            'payee' => [
                'merchant_id' => 'merchant-id',
            ],
            'payment_instruction' => [
                'platform_fees' => [[
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => '1.00',
                    ],
                    'payee' => [
                        'merchant_id' => 'A1B2C3D4E5F6G',
                    ],
                ]],
                'disbursement_mode' => PaymentInstruction::DISBURSEMENT_MODE_INSTANT,
                'payee_pricing_tier_id' => 'tier-1',
                'payee_receivable_fx_rate_id' => 'fx-rate-1',
            ],
        ]);

        $paymentInstruction = $purchaseUnit->getPaymentInstruction();
        static::assertNotNull($paymentInstruction);
        static::assertSame(PaymentInstruction::DISBURSEMENT_MODE_INSTANT, $paymentInstruction->getDisbursementMode());
        static::assertSame('tier-1', $paymentInstruction->getPayeePricingTierId());
        static::assertSame('fx-rate-1', $paymentInstruction->getPayeeReceivableFxRateId());
        static::assertCount(1, $paymentInstruction->getPlatformFees());
        static::assertInstanceOf(PlatformFee::class, $paymentInstruction->getPlatformFees()->first());

        static::assertSame([
            'reference_id' => 'default',
            'amount' => [
                'breakdown' => null,
                'currency_code' => 'USD',
                'value' => '10.00',
            ],
            'payee' => [
                'merchant_id' => 'merchant-id',
            ],
            'payment_instruction' => [
                'platform_fees' => [[
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => '1.00',
                    ],
                    'payee' => [
                        'merchant_id' => 'A1B2C3D4E5F6G',
                    ],
                ]],
                'disbursement_mode' => PaymentInstruction::DISBURSEMENT_MODE_INSTANT,
                'payee_pricing_tier_id' => 'tier-1',
                'payee_receivable_fx_rate_id' => 'fx-rate-1',
            ],
        ], \json_decode((string) \json_encode($purchaseUnit, \JSON_THROW_ON_ERROR), true, 512, \JSON_THROW_ON_ERROR));
    }

    public function testPaymentInstructionPayeePricingTierIdLengthValidation(): void
    {
        $paymentInstruction = new PaymentInstruction();

        $this->expectException(\LengthException::class);

        $paymentInstruction->setPayeePricingTierId(\str_repeat('a', PaymentInstruction::MAX_LENGTH_PAYEE_PRICING_TIER_ID + 1));
    }

    public function testPaymentInstructionPayeeReceivableFxRateIdLengthValidation(): void
    {
        $paymentInstruction = new PaymentInstruction();

        $this->expectException(\LengthException::class);

        $paymentInstruction->setPayeeReceivableFxRateId(\str_repeat('a', PaymentInstruction::MAX_LENGTH_PAYEE_RECEIVABLE_FX_RATE_ID + 1));
    }
}
