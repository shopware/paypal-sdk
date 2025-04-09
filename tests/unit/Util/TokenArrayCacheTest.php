<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Util;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Struct\V1\Token;
use Shopware\PayPalSDK\Util\TokenArrayCache;

/**
 * @internal
 */
#[CoversClass(TokenArrayCache::class)]
class TokenArrayCacheTest extends TestCase
{
    public function testInitialToken(): void
    {
        $token = new Token();
        $cache = new TokenArrayCache(['key' => $token]);

        static::assertSame($token, $cache->get('key'));
    }

    public function testSetGet(): void
    {
        $token = new Token();
        $cache = new TokenArrayCache();

        $cache->set('key', $token);

        static::assertSame($token, $cache->get('key'));

        $token2 = new Token();
        $cache->set('key', $token2);

        static::assertSame($token2, $cache->get('key'));
        static::assertNotSame($token, $cache->get('key'));
    }

    public function testGetDefault(): void
    {
        $cache = new TokenArrayCache();

        static::assertFalse($cache->has('key'));
        static::assertNull($cache->get('key', null));
    }

    public function testDelete(): void
    {
        $token = new Token();
        $cache = new TokenArrayCache(['key' => $token]);

        $cache->delete('key');

        static::assertNull($cache->get('key'));
        static::assertFalse($cache->has('key'));
    }

    public function testClear(): void
    {
        $token = new Token();
        $cache = new TokenArrayCache(['key' => $token]);

        $cache->clear();

        static::assertNull($cache->get('key'));
        static::assertFalse($cache->has('key'));
    }

    public function testHas(): void
    {
        $token = new Token();
        $cache = new TokenArrayCache();

        static::assertNull($cache->get('key'));
        static::assertFalse($cache->has('key'));

        $cache->set('key', $token);

        static::assertSame($token, $cache->get('key'));
        static::assertTrue($cache->has('key'));
    }

    public function testGetMultiple(): void
    {
        $token1 = new Token();
        $token2 = new Token();
        $token3 = new Token();

        $cache = new TokenArrayCache([
            'key1' => $token1,
            'key2' => $token2,
            'key3' => $token3,
        ]);

        static::assertSame(
            ['key2' => $token2, 'key3' => $token3],
            \iterator_to_array($cache->getMultiple(['key2', 'key3'])),
        );
        static::assertSame(
            ['key3' => $token3, 'key1' => $token1],
            \iterator_to_array($cache->getMultiple(['key3', 'key1'])),
        );
        static::assertSame(
            ['key1' => $token1, 'not-existing' => null],
            \iterator_to_array($cache->getMultiple(['key1', 'not-existing'])),
        );
    }

    public function testSetMultiple(): void
    {
        $token1 = new Token();
        $token2 = new Token();
        $token3 = new Token();

        $cache = new TokenArrayCache();
        $cache->setMultiple([
            'key1' => $token1,
            'key2' => $token2,
            'key3' => $token3,
        ]);

        static::assertSame(
            ['key2' => $token2, 'key3' => $token3],
            \iterator_to_array($cache->getMultiple(['key2', 'key3'])),
        );
        static::assertSame(
            ['key3' => $token3, 'key1' => $token1],
            \iterator_to_array($cache->getMultiple(['key3', 'key1'])),
        );
        static::assertSame(
            ['key1' => $token1, 'not-existing' => null],
            \iterator_to_array($cache->getMultiple(['key1', 'not-existing'])),
        );
    }

    public function testDeleteMultiple(): void
    {
        $token1 = new Token();
        $token2 = new Token();
        $token3 = new Token();

        $cache = new TokenArrayCache([
            'key1' => $token1,
            'key2' => $token2,
            'key3' => $token3,
        ]);

        $cache->deleteMultiple(['key1', 'key2', 'non-existing']);

        static::assertFalse($cache->has('key1'));
        static::assertFalse($cache->has('key2'));
        static::assertTrue($cache->has('key3'));
        static::assertFalse($cache->has('non-existing'));
    }
}
