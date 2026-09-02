<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Struct\V1;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Struct\V1\Token;

/**
 * @internal
 */
#[CoversClass(Token::class)]
class TokenTest extends TestCase
{
    public function testTokenIsSensitive(): void
    {
        $token = (new Token())->assign([
            'access_token' => 'some-access-token',
            'expires_in' => 36000,
        ]);

        static::assertSame(Token::class . " Object\n(\n)\n", \print_r($token, true));
        static::assertEmpty($token->jsonSerialize());
    }

    public function testIsNotCachedByDefault(): void
    {
        static::assertFalse((new Token())->isCached());

        $token = (new Token())->assign([
            'access_token' => 'some-access-token',
            'expires_in' => 36000,
        ]);

        static::assertFalse($token->isCached());
    }

    public function testSetCached(): void
    {
        $token = new Token();

        $token->setCached(true);
        static::assertTrue($token->isCached());

        $token->setCached(false);
        static::assertFalse($token->isCached());
    }

    public function testCachedIsNotAssignedFromResponseData(): void
    {
        $token = (new Token())->assign([
            'access_token' => 'some-access-token',
            'expires_in' => 36000,
            'cached' => true,
        ]);

        static::assertFalse($token->isCached(), 'The cache flag must be controlled by the SDK, not by the API response.');
    }

    public function testCachedIsNotSerialized(): void
    {
        $token = (new Token())->assign([
            'access_token' => 'some-access-token',
            'expires_in' => 36000,
        ]);
        $token->setCached(true);

        static::assertEmpty($token->jsonSerialize());
        static::assertSame(Token::class . " Object\n(\n)\n", \print_r($token, true));
    }
}
