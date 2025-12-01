<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Context;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Context\ApiContext;
use Shopware\PayPalSDK\Context\ClientTokenOAuthContext;

/**
 * @internal
 */
#[CoversClass(ClientTokenOAuthContext::class)]
class ClientTokenOAuthContextTest extends TestCase
{
    public function testBodyAndHeader(): void
    {
        $context = new ClientTokenOAuthContext(
            'some-client-id',
            'some-client-secret',
        );

        static::assertEquals([
            'grant_type' => 'client_credentials',
            'response_type' => 'client_token',
        ], $context->getBody());
        static::assertEquals([
            'Authorization' => 'Basic c29tZS1jbGllbnQtaWQ6c29tZS1jbGllbnQtc2VjcmV0',
        ], $context->getHeaders());
        static::assertSame('some-client-id', $context->getClientId());
    }

    public function testCacheKey(): void
    {
        $oauthContext = new ClientTokenOAuthContext(
            'some-client-id',
            'some-client-secret',
        );

        $context = new ApiContext($oauthContext, true);

        static::assertSame('83b27fb1aba69ca6df73f52b7323b28f', $oauthContext->getCacheKey($context));
        static::assertSame('7c9d85e352fd8fe30e4c1ca88e266e99', $oauthContext->getCacheKey($context->withSandbox(false)));
    }

    public function testDebugInformationSensitive(): void
    {
        $oauthContext = new ClientTokenOAuthContext(
            'some-client-id',
            'some-client-secret',
        );

        static::assertSame(ClientTokenOAuthContext::class . " Object\n(\n)\n", \print_r($oauthContext, true));
    }

    /**
     * @param array<string> $input
     * @param array<string> $expected
     */
    #[DataProvider('domainProvider')]
    public function testWithDomains(array $input, array $expected): void
    {
        $oauthContext = new ClientTokenOAuthContext(
            'some-client-id',
            'some-client-secret',
        );

        $oauthContext = $oauthContext->withDomains($input);
        static::assertSame($expected, $oauthContext->getDomains());
    }

    public static function domainProvider(): \Generator
    {
        yield 'valid domains' => [
            ['example.com', 'shopware.com'],
            ['example.com', 'shopware.com'],
        ];

        yield 'domains with subdomains' => [
            ['https://sub.example.com', 'test.shopware.com', 'deep.nested.sub.domain.test.com'],
            ['example.com', 'shopware.com', 'test.com'],
        ];

        yield 'domains with schemes' => [
            ['http://example.com', 'https://sub.shopware.com', 'mailto://test@test.com'],
            ['example.com', 'shopware.com', 'test.com'],
        ];

        yield 'domains without top-level' => [
            ['localhost', 'some-local-domain'],
            [],
        ];

        yield 'mixed valid and invalid domains' => [
            ['example.com', 'invalid_domain', 'sub.shopware.com', 'localhost'],
            ['example.com', 'shopware.com'],
        ];

        yield 'duplicate domains' => [
            ['example.com', 'shopware.com', 'example.com', 'shopware.com'],
            ['example.com', 'shopware.com'],
        ];

        yield 'complex domains' => [
            ['example.com/some/path', 'sub.domain.shopware.com/path', 'https://test.com/?query=string#anchor', 'xn--mnchen-3ya.com:3000'],
            ['example.com', 'shopware.com', 'test.com', 'xn--mnchen-3ya.com'],
        ];

        yield 'IPs' => [
            ['127.0.0.1', 'https://172.0.1.1', 'http://127.0.0.2:2000/path', 'https://[::1]:443/path', '::1'],
            [],
        ];
    }
}
