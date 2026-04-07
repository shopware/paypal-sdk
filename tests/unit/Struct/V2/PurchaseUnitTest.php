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
            'custom_id' => null,
            'invoice_id' => null,
            'items' => null,
            'shipping' => null,
            'payments' => null,
            'shipping_options' => null,
        ], \json_decode((string) \json_encode($purchaseUnit, \JSON_THROW_ON_ERROR), true, 512, \JSON_THROW_ON_ERROR));
    }
}
