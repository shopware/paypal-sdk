<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Util;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Util\Utf16;

/**
 * @internal
 */
#[CoversClass(Utf16::class)]
class Utf16Test extends TestCase
{
    /**
     * @return iterable<string, array{string, int}>
     */
    public static function provideLength(): iterable
    {
        yield 'empty' => ['', 0];
        yield 'ascii' => ['abc', 3];
        yield 'two-byte character' => ['ä', 1];
        yield 'three-byte character' => ['€', 1];
        yield 'emoji is a surrogate pair' => ['😀', 2];
        yield 'mixed' => ['xä€😀', 5];
    }

    #[DataProvider('provideLength')]
    public function testLength(string $value, int $expected): void
    {
        static::assertSame($expected, Utf16::length($value));
    }

    public function testTruncateKeepsValueWithinLimit(): void
    {
        static::assertSame('abc', Utf16::truncate('abc', 3));
        static::assertSame(\str_repeat('😀', 63), Utf16::truncate(\str_repeat('😀', 63), 127));
    }

    public function testTruncateCutsToUnits(): void
    {
        static::assertSame('ab', Utf16::truncate('abc', 2));
        static::assertSame(\str_repeat('ä', 127), Utf16::truncate(\str_repeat('ä', 200), 127));
    }

    public function testTruncateNeverSplitsASurrogatePair(): void
    {
        // unit 127 is the first half of the 64th emoji
        $truncated = Utf16::truncate(\str_repeat('😀', 64), 127);

        static::assertSame(\str_repeat('😀', 63), $truncated);
        static::assertSame(126, Utf16::length($truncated));
        static::assertTrue(\mb_check_encoding($truncated, 'UTF-8'));
    }

    public function testTruncateAtPairBoundary(): void
    {
        // the leading 'x' makes unit 127 the second half of the 63rd emoji
        static::assertSame('x' . \str_repeat('😀', 63), Utf16::truncate('x' . \str_repeat('😀', 70), 127));
    }

    public function testTruncateToNothing(): void
    {
        static::assertSame('', Utf16::truncate('abc', 0));
        static::assertSame('', Utf16::truncate('😀', 1));
    }
}
