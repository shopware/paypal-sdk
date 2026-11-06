<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Struct\V2;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Struct\ConstantsV2;
use Shopware\PayPalSDK\Struct\V2\Order;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnitCollection;

/**
 * @internal
 */
#[CoversClass(Order::class)]
class OrderTest extends TestCase
{
    public function testMissingIntentIsNotInferred(): void
    {
        $order = (new Order())->assign(['id' => 'ORDER-123']);

        static::assertFalse($order->isset('intent'));
        static::assertNull($order->getIntent());
        static::assertArrayNotHasKey('intent', $order->jsonSerialize());
    }

    public function testIntentMustBeSetExplicitly(): void
    {
        $order = new Order();
        $order->setIntent(ConstantsV2::INTENT_CAPTURE);

        static::assertSame(ConstantsV2::INTENT_CAPTURE, $order->getIntent());
        static::assertSame(ConstantsV2::INTENT_CAPTURE, $order->jsonSerialize()['intent']);
    }

    public function testMissingResponseFieldsAreReadableWithoutChangingSerialization(): void
    {
        $order = (new Order())->assign(['id' => 'ORDER-123']);

        static::assertNull($order->getCreateTime());
        static::assertNull($order->getUpdateTime());
        static::assertNull($order->getPayer());
        static::assertCount(0, $order->getPurchaseUnits());
        static::assertNull($order->getApplicationContext());
        static::assertNull($order->getPaymentSource());
        static::assertNull($order->getStatus());
        static::assertNull($order->getProcessingInstruction());
        static::assertNull($order->getLinks());
        static::assertSame(['id' => 'ORDER-123'], $order->jsonSerialize());
    }

    public function testNullAndEmptyAssignmentsPreserveExistingValues(): void
    {
        $order = (new Order())->assign([
            'payer' => ['email_address' => 'buyer@example.com'],
            'purchase_units' => [['reference_id' => 'old']],
        ]);
        $previous = $order->jsonSerialize();
        $order->assign(['payer' => null, 'purchase_units' => []]);

        static::assertSame($previous, $order->jsonSerialize());
        static::assertCount(1, $order->getPurchaseUnits());
    }

    public function testSerializationOmitsEmptyFieldsSetDirectly(): void
    {
        $order = new Order();
        $order->setIntent(ConstantsV2::INTENT_CAPTURE);
        $order->setPayer(null);
        $order->setPurchaseUnits(new PurchaseUnitCollection());
        $order->setProcessingInstruction('');

        static::assertSame(['intent' => ConstantsV2::INTENT_CAPTURE], $order->jsonSerialize());
    }
}
