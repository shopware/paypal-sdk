<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Struct\V2\Order\PurchaseUnit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Item;
use Shopware\PayPalSDK\Util\Utf16;

/**
 * @internal
 */
#[CoversClass(Item::class)]
#[UsesClass(Utf16::class)]
class ItemTest extends TestCase
{
    /**
     * @return iterable<string, array{string}>
     */
    public static function provideAcceptsNamesPayPalKeepsUnchanged(): iterable
    {
        yield '127 ascii' => [\str_repeat('a', 127)];
        yield '127 two-byte characters' => [\str_repeat('ä', 127)];
        yield '127 three-byte characters' => [\str_repeat('€', 127)];
        yield '63 emoji, 126 units' => [\str_repeat('😀', 63)];
        yield 'name as PayPal returns a cut one' => [\str_repeat('a', 124) . '...'];
    }

    #[DataProvider('provideAcceptsNamesPayPalKeepsUnchanged')]
    public function testAcceptsNamesPayPalKeepsUnchanged(string $name): void
    {
        $item = new Item();
        $item->setName($name);

        static::assertSame($name, $item->getName());
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideRejectsNamesPayPalWouldCut(): iterable
    {
        yield '128 ascii' => [\str_repeat('a', 128)];
        yield '128 two-byte characters' => [\str_repeat('ä', 128)];
        yield '64 emoji, 128 units' => [\str_repeat('😀', 64)];
        yield '71 characters PayPal would split an emoji in' => ['x' . \str_repeat('😀', 70)];
    }

    #[DataProvider('provideRejectsNamesPayPalWouldCut')]
    public function testRejectsNamesPayPalWouldCut(string $name): void
    {
        $this->expectException(\LengthException::class);
        $this->expectExceptionMessage('must not be longer than 127 UTF-16 code units');

        (new Item())->setName($name);
    }

    public function testTruncatedNameIsAccepted(): void
    {
        $item = new Item();
        $item->setName(Utf16::truncate('x' . \str_repeat('😀', 70), Item::MAX_LENGTH_NAME));

        static::assertSame('x' . \str_repeat('😀', 63), $item->getName());
    }
}
