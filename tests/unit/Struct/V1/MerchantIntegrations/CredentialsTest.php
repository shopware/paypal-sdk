<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Struct\V1\MerchantIntegrations;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Struct\V1\MerchantIntegrations\Credentials;

/**
 * @internal
 */
#[CoversClass(Credentials::class)]
class CredentialsTest extends TestCase
{
    public function testTokenIsSensitive(): void
    {
        $credentials = (new Credentials())->assign([
            'client_id' => 'some-client-id',
            'client_secret' => 'some-client-secret',
        ]);

        static::assertSame(Credentials::class . " Object\n(\n)\n", \print_r($credentials, true));
        static::assertEmpty($credentials->jsonSerialize());
    }
}
