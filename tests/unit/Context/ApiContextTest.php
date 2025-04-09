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
#[CoversClass(ApiContext::class)]
class ApiContextTest extends TestCase
{
    public function testConstructAndGetter(): void
    {
        $oauthContext = new CredentialsOAuthContext('', '');
        $context = new ApiContext(
            $oauthContext,
            true,
            'merchantId',
            [
                'PayPal-Header' => 'header-value',
                'wEiRd_CaSiNg' => 'vAlUe',
                'header-to-remove' => null,
            ],
            [
                'query-param' => 'query-value',
                'wEiRd_CaSiNg' => 'vAlUe',
                'query-to-remove' => null,
            ],
            true,
        );

        static::assertSame($oauthContext, $context->getOAuthContext());
        static::assertTrue($context->isSandbox());
        static::assertSame('merchantId', $context->getMerchantId());
        static::assertSame(['paypal-header' => 'header-value', 'weird_casing' => 'vAlUe'], $context->getHeaders());
        static::assertSame(['query-param' => 'query-value', 'wEiRd_CaSiNg' => 'vAlUe'], $context->getQueryParameters());
        static::assertSame('header-value', $context->getHeader('paypal-header'));
        static::assertSame('query-value', $context->getQueryParameter('query-param'));

        static::assertSame('vAlUe', $context->getHeader('weird_casing'));
        static::assertSame('vAlUe', $context->getHeader('wEiRd_CaSiNg'));
        static::assertNull($context->getHeader('header-to-remove'));

        static::assertNull($context->getQueryParameter('weird_casing'));
        static::assertNull($context->getQueryParameter('query-to-remove'));
        static::assertSame('vAlUe', $context->getQueryParameter('wEiRd_CaSiNg'));
    }

    public function testWithOAuthContext(): void
    {
        $oauthContext = new CredentialsOAuthContext('', '');
        $context = new ApiContext($oauthContext, true);

        $newContext = $context->withOAuthContext($oauthContext);

        static::assertNotSame($context, $newContext);
        static::assertEquals($context, $newContext);
        static::assertSame($oauthContext, $newContext->getOAuthContext());

        $newOauthContext = new CredentialsOAuthContext('', '');
        $newContext = $context->withOAuthContext($newOauthContext);

        static::assertNotSame($context, $newContext);
        static::assertSame($newOauthContext, $newContext->getOAuthContext());
        static::assertSame($oauthContext, $context->getOAuthContext());
    }

    public function testWithSandbox(): void
    {
        $oauthContext = new CredentialsOAuthContext('', '');
        $context = new ApiContext($oauthContext, false);

        $newContext = $context->withSandbox(true);

        static::assertNotSame($context, $newContext);
        static::assertTrue($newContext->isSandbox());
        static::assertFalse($context->isSandbox());
    }

    public function testWithMerchantId(): void
    {
        $oauthContext = new CredentialsOAuthContext('', '');
        $context = new ApiContext($oauthContext, true, 'merchantId');

        $newContext = $context->withMerchantId('anotherMerchantId');

        static::assertNotSame($context, $newContext);
        static::assertSame('merchantId', $context->getMerchantId());
        static::assertSame('anotherMerchantId', $newContext->getMerchantId());
    }

    public function testWithHeader(): void
    {
        $headers = ['paypal-header' => 'header-value'];

        $oauthContext = new CredentialsOAuthContext('', '');
        $context = new ApiContext($oauthContext, true, headers: $headers);

        $newContext = $context->withHeader('pAYpal-heaDER', 'someId');

        static::assertNotSame($context, $newContext);
        static::assertEquals(['paypal-header' => 'header-value'], $context->getHeaders());
        static::assertEquals(['paypal-header' => 'someId'], $newContext->getHeaders());

        $newContext = $context->withHeader('paypal-header', null);

        static::assertNotSame($context, $newContext);
        static::assertEmpty($newContext->getHeaders());
    }

    public function testWithQueryParameter(): void
    {
        $queryParameters = ['Query-Param' => 'Query-Value'];

        $oauthContext = new CredentialsOAuthContext('', '');
        $context = new ApiContext($oauthContext, true, queryParameters: $queryParameters);

        $newContext = $context->withQueryParameter('Query-Param', 'someValue');

        static::assertNotSame($context, $newContext);
        static::assertSame($queryParameters, $context->getQueryParameters());
        static::assertEquals(['Query-Param' => 'someValue'], $newContext->getQueryParameters());

        $newContext = $context->withQueryParameter('Query-Param', null);

        static::assertNotSame($context, $newContext);
        static::assertEmpty($newContext->getQueryParameters());

        $newContext = $context->withQueryParameter('query-param', 'someValue');

        static::assertNotSame($context, $newContext);
        static::assertEquals(['Query-Param' => 'Query-Value', 'query-param' => 'someValue'], $newContext->getQueryParameters());
    }

    public function testWithThirdParty(): void
    {
        $oauthContext = new CredentialsOAuthContext('', '');
        $context = new ApiContext($oauthContext, true, thirdParty: true);

        $newContext = $context->withThirdParty(false);

        static::assertNotSame($context, $newContext);
        static::assertFalse($newContext->isThirdParty());
        static::assertTrue($context->isThirdParty());
    }

    public function testWithPartnerAttributionId(): void
    {
        $headers = ['paypal-partner-attribution-id' => 'header-value'];

        $oauthContext = new CredentialsOAuthContext('', '');
        $context = new ApiContext($oauthContext, true, headers: $headers);

        $newContext = $context->withPartnerAttributionId('partnerAttributionId');

        static::assertNotSame($context, $newContext);
        static::assertSame($headers, $context->getHeaders());
        static::assertEquals(['paypal-partner-attribution-id' => 'partnerAttributionId'], $newContext->getHeaders());

        $newContext = $context->withPartnerAttributionId(null);

        static::assertNotSame($context, $newContext);
        static::assertEmpty($newContext->getHeaders());
    }

    public function testWithPreferRepresentation(): void
    {
        $headers = ['prefer' => 'header-value'];

        $oauthContext = new CredentialsOAuthContext('', '');
        $context = new ApiContext($oauthContext, true, headers: $headers);

        $newContext = $context->withPreferRepresentation(true);

        static::assertNotSame($context, $newContext);
        static::assertSame($headers, $context->getHeaders());
        static::assertEquals(['prefer' => 'return=representation'], $newContext->getHeaders());

        $newContext = $context->withPreferRepresentation(false);

        static::assertNotSame($context, $newContext);
        static::assertEmpty($newContext->getHeaders());
    }

    public function testWithRequestId(): void
    {
        $headers = ['paypal-request-id' => 'header-value'];

        $oauthContext = new CredentialsOAuthContext('', '');
        $context = new ApiContext($oauthContext, true, headers: $headers);

        $newContext = $context->withRequestId('requestId');

        static::assertNotSame($context, $newContext);
        static::assertSame($headers, $context->getHeaders());
        static::assertEquals(['paypal-request-id' => 'requestId'], $newContext->getHeaders());

        $newContext = $context->withRequestId(null);

        static::assertNotSame($context, $newContext);
        static::assertEmpty($newContext->getHeaders());
    }

    public function testWithClientMetadataId(): void
    {
        $headers = ['paypal-client-metadata-id' => 'header-value'];

        $oauthContext = new CredentialsOAuthContext('', '');
        $context = new ApiContext($oauthContext, true, headers: $headers);

        $newContext = $context->withClientMetadataId('clientMetadataId');

        static::assertNotSame($context, $newContext);
        static::assertSame($headers, $context->getHeaders());
        static::assertEquals(['paypal-client-metadata-id' => 'clientMetadataId'], $newContext->getHeaders());

        $newContext = $context->withClientMetadataId(null);

        static::assertNotSame($context, $newContext);
        static::assertEmpty($newContext->getHeaders());
    }
}
