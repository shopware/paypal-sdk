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
        static::assertArrayNotHasKey('intent', $order->jsonSerialize());
    }

    public function testIntentMustBeSetExplicitly(): void
    {
        $order = new Order();
        $order->setIntent(ConstantsV2::INTENT_CAPTURE);

        static::assertSame(ConstantsV2::INTENT_CAPTURE, $order->getIntent());
        static::assertSame(ConstantsV2::INTENT_CAPTURE, $order->jsonSerialize()['intent']);
    }
}
