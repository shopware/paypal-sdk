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
use Shopware\PayPalSDK\Context\ClientTokenOAuthContext;
use Shopware\PayPalSDK\Context\CredentialsOAuthContext;
use Shopware\PayPalSDK\Context\UserIdOAuthContext;

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

        static::assertSame('811d31b1a38f983389f5ea9282138dc6', $oauthContext->getCacheKey($context));
        static::assertSame('07d2bb003e7335920000cd7050f8c839', $oauthContext->getCacheKey($context->withSandbox(false)));

        $context = $context->withThirdParty(false)->withMerchantId('some-merchant-id');
        static::assertSame('811d31b1a38f983389f5ea9282138dc6', $oauthContext->getCacheKey($context));

        // cache key has to be differ from the ones above if third-party and merchant id is set
        $context = $context->withThirdParty(true);
        static::assertSame('e5962bdc163c23423cff6c704e598863', $oauthContext->getCacheKey($context));
    }

    public function testIntoUserIdContext(): void
    {
        $oauthContext = (new CredentialsOAuthContext('some-client-id', 'some-client-secret'))
            ->intoUserIdContext('some-target-user-id');

        $context = new ApiContext($oauthContext, true);

        /** @phpstan-ignore-next-line staticMethod.alreadyNarrowedType - still worth the assertion */
        static::assertInstanceOf(UserIdOAuthContext::class, $oauthContext);
        static::assertSame('80df4af5b9a9bc898ed7a417362877dc', $oauthContext->getCacheKey($context));
    }

    public function testIntoUserIdContextWithoutId(): void
    {
        $oauthContext = (new CredentialsOAuthContext('some-client-id', 'some-client-secret'))
            ->intoUserIdContext(null);

        $context = new ApiContext($oauthContext, true);

        static::assertSame('2088739d61790f3ccd0135f7bb22c118', $oauthContext->getCacheKey($context));
    }

    public function testIntoClientTokenContext(): void
    {
        $oauthContext = (new CredentialsOAuthContext('some-client-id', 'some-client-secret'))
            ->intoClientTokenContext();

        $context = new ApiContext($oauthContext, true);

        /** @phpstan-ignore-next-line staticMethod.alreadyNarrowedType - still worth the assertion */
        static::assertInstanceOf(ClientTokenOAuthContext::class, $oauthContext);
        static::assertSame('6f476c3bbc49048238b87c70f7a9deea', $oauthContext->getCacheKey($context));
    }

    public function testDebugInformationSensitive(): void
    {
        $oauthContext = new CredentialsOAuthContext('some-client-id', 'some-client-secret');

        static::assertSame(CredentialsOAuthContext::class . " Object\n(\n)\n", \print_r($oauthContext, true));
    }
}
