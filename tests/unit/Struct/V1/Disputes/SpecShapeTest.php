<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Struct\V1\Disputes;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Struct\V1\Disputes\Common\ProductDetails;
use Shopware\PayPalSDK\Struct\V1\Disputes\Common\ServiceDetails;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\Evidence\EvidenceInfo;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\MoneyMovement;

/**
 * Payloads shaped as customer_disputes_v1 defines them.
 *
 * @internal
 */
#[CoversClass(MoneyMovement::class)]
#[CoversClass(ProductDetails::class)]
#[CoversClass(ServiceDetails::class)]
#[CoversClass(EvidenceInfo::class)]
class SpecShapeTest extends TestCase
{
    public function testMoneyMovementAmountIsMoney(): void
    {
        $movement = (new MoneyMovement())->assign(['amount' => ['currency_code' => 'EUR', 'value' => '12.34']]);

        static::assertSame('EUR', $movement->getAmount()->getCurrencyCode());
        static::assertSame('12.34', $movement->getAmount()->getValue());
    }

    public function testSubReasonsAreStrings(): void
    {
        $product = (new ProductDetails())->assign(['sub_reasons' => ['DAMAGED', 'DIFFERENT']]);
        $service = (new ServiceDetails())->assign(['sub_reasons' => ['INCOMPLETE']]);

        static::assertSame(['DAMAGED', 'DIFFERENT'], $product->getSubReasons());
        static::assertSame(['INCOMPLETE'], $service->getSubReasons());
        static::assertSame(['DAMAGED', 'DIFFERENT'], $product->jsonSerialize()['sub_reasons']);
    }

    public function testRefundIdsAreStrings(): void
    {
        $evidence = (new EvidenceInfo())->assign(['refund_ids' => ['5HU34657CE7713041']]);

        static::assertSame(['5HU34657CE7713041'], $evidence->getRefundIds());
    }

    public function testAbsentStringListsReadEmpty(): void
    {
        static::assertSame([], (new ProductDetails())->getSubReasons());
        static::assertSame([], (new EvidenceInfo())->getRefundIds());
    }
}
