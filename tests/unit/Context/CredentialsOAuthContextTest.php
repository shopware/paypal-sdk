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
use Shopware\PayPalSDK\Context\CredentialsOAuthContext;

/**
 * @internal
 */
#[CoversClass(CredentialsOAuthContext::class)]
class CredentialsOAuthContextTest extends TestCase
{
    public function testBodyAndHeader(): void
    {
        $context = new CredentialsOAuthContext('some-client-id', 'some-client-secret');

        static::assertEquals(['grant_type' => 'client_credentials'], $context->getBody());
        static::assertEquals([
            'Authorization' => 'Basic c29tZS1jbGllbnQtaWQ6c29tZS1jbGllbnQtc2VjcmV0',
        ], $context->getHeaders());
        static::assertSame('some-client-id', $context->getClientId());
    }

    public function testCacheKey(): void
    {
        $oauthContext = new CredentialsOAuthContext('some-client-id', 'some-client-secret');

        $context = new ApiContext($oauthContext, true);

        static::assertSame('806098f9f6e2fbbe6b3f402dad7c0220', $oauthContext->getCacheKey($context));
        static::assertSame('86ab9591f53eba173b77612390d2416a', $oauthContext->getCacheKey($context->withSandbox(false)));
    }

    public function testIntoUserIdContext(): void
    {
        $oauthContext = (new CredentialsOAuthContext('some-client-id', 'some-client-secret'))
            ->intoUserIdContext('some-target-user-id');

        $context = new ApiContext($oauthContext, true);

        static::assertSame('53bf912f9ef7bee5ce0e44d7644f5566', $oauthContext->getCacheKey($context));
    }

    public function testIntoUserIdContextWithoutId(): void
    {
        $oauthContext = (new CredentialsOAuthContext('some-client-id', 'some-client-secret'))
            ->intoUserIdContext(null);

        $context = new ApiContext($oauthContext, true);

        static::assertSame('db4395115b84188b4fe25782e010ac8c', $oauthContext->getCacheKey($context));
    }
}
