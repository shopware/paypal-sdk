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
use Shopware\PayPalSDK\Context\AuthorizationCodeOAuthContext;

/**
 * @internal
 */
#[CoversClass(AuthorizationCodeOAuthContext::class)]
class AuthorizationCodeOAuthContextTest extends TestCase
{
    public function testBodyAndHeader(): void
    {
        $context = new AuthorizationCodeOAuthContext(
            'some-auth-code',
            'some-shared-id',
            'some-nonce',
        );

        static::assertEquals([
            'grant_type' => 'authorization_code',
            'code' => 'some-auth-code',
            'code_verifier' => 'some-nonce',
        ], $context->getBody());
        static::assertEquals([
            'Authorization' => 'Basic c29tZS1zaGFyZWQtaWQ6',
        ], $context->getHeaders());
    }

    public function testCacheKey(): void
    {
        $oauthContext = new AuthorizationCodeOAuthContext(
            'some-auth-code',
            'some-shared-id',
            'some-nonce',
        );

        $context = new ApiContext($oauthContext, true);

        static::assertSame('582eef4682b1b033733d4a8363341b33', $oauthContext->getCacheKey($context));
        static::assertSame('e026f666d78e3ba2387af64f8ed65b59', $oauthContext->getCacheKey($context->withSandbox(false)));
    }

    public function testDebugInformationSensitive(): void
    {
        $oauthContext = new AuthorizationCodeOAuthContext(
            'some-auth-code',
            'some-shared-id',
            'some-nonce',
        );

        static::assertSame(AuthorizationCodeOAuthContext::class . " Object\n(\n)\n", \print_r($oauthContext, true));
    }
}
