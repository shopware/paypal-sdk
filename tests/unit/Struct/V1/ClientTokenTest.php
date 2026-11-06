<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Struct\V1;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Struct\V1\ClientToken;

/**
 * @internal
 */
#[CoversClass(ClientToken::class)]
class ClientTokenTest extends TestCase
{
    public function testExpirationDateCannotBeModifiedThroughGetter(): void
    {
        $token = (new ClientToken())->assign(['client_token' => 'test-token', 'expires_in' => 3600]);
        $expiration = $token->getExpireDateTime();
        $changed = $expiration->modify('+1 day');

        static::assertSame($expiration, $token->getExpireDateTime());
        static::assertNotSame($expiration, $changed);

        $token->setExpireDateTime($changed);
        static::assertSame($changed, $token->getExpireDateTime());
    }
}
