<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Context;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Context\ApiContext;
use Shopware\PayPalSDK\Context\UserIdOAuthContext;

/**
 * @internal
 */
#[CoversClass(UserIdOAuthContext::class)]
class UserIdOAuthContextTest extends TestCase
{
    public function testBodyAndHeader(): void
    {
        $context = new UserIdOAuthContext(
            'some-client-id',
            'some-client-secret',
            'some-target-user-id',
        );

        static::assertEquals([
            'grant_type' => 'client_credentials',
            'response_type' => 'id_token',
            'target_customer_id' => 'some-target-user-id',
        ], $context->getBody());
        static::assertEquals([
            'Authorization' => 'Basic c29tZS1jbGllbnQtaWQ6c29tZS1jbGllbnQtc2VjcmV0',
        ], $context->getHeaders());
        static::assertSame('some-client-id', $context->getClientId());
    }

    public function testCacheKey(): void
    {
        $oauthContext = new UserIdOAuthContext(
            'some-client-id',
            'some-client-secret',
            'some-target-user-id',
        );

        $context = new ApiContext($oauthContext, true);

        static::assertSame('53bf912f9ef7bee5ce0e44d7644f5566', $oauthContext->getCacheKey($context));
        static::assertSame('38cc2b78bcff06448a4607ff03ff2f57', $oauthContext->getCacheKey($context->withSandbox(false)));
    }

    public function testDebugInformationSensitive(): void
    {
        $oauthContext = new UserIdOAuthContext(
            'some-client-id',
            'some-client-secret',
            'some-target-user-id',
        );

        static::assertSame(UserIdOAuthContext::class . " Object\n(\n)\n", \print_r($oauthContext, true));
    }
}
