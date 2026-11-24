<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Struct\V2\Order\PurchaseUnit\Shipping\Tracker;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Shipping\Tracker\Item;

/**
 * @internal
 */
#[CoversClass(Item::class)]
class ItemTest extends TestCase
{
    public function testNameIsNotLimited(): void
    {
        $name = \str_repeat('a', 10000);

        $item = new Item();
        $item->setName($name);

        static::assertSame($name, $item->getName());
    }

    public function testResponseWithLongEmojiNameIsReadable(): void
    {
        // 71 characters, 141 UTF-16 units
        $name = 'x' . \str_repeat('😀', 70);

        $item = (new Item())->assign(['name' => $name, 'quantity' => '1']);

        static::assertSame($name, $item->getName());
    }
}
