<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Test\Request;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Constants;
use Shopware\PayPalSDK\Context\ApiContext;
use Shopware\PayPalSDK\Context\CredentialsOAuthContext;
use Shopware\PayPalSDK\Gateway\CustomerGateway;
use Shopware\PayPalSDK\Gateway\OrderGateway;
use Shopware\PayPalSDK\Test\Request\TestRequestContext;

/**
 * @internal
 */
#[CoversClass(TestRequestContext::class)]
class TestRequestContextTest extends TestCase
{
    public function test(): void
    {
        $apiContext = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true);

        $request = new Request('POST', Constants::BASEURL_SANDBOX . '/v2/orders/id');

        $body = ['id' => 'some-order-id'];

        $context = new TestRequestContext(
            OrderGateway::class,
            'getOrder',
            $request,
            $apiContext,
            $body,
        );

        static::assertSame(OrderGateway::class, $context->getGatewayClass());
        static::assertSame('getOrder', $context->getGatewayMethod());
        static::assertSame($request, $context->getRequest());
        static::assertSame($body, $context->getRequestBody());
        static::assertSame($apiContext, $context->getContext());
        static::assertTrue($context->isGateway(OrderGateway::class));
        static::assertFalse($context->isGateway(CustomerGateway::class));

        $response = new Response();
        $context = $context->withResponse($response);

        static::assertSame($response, $context->getResponse());
    }

    public function testWithoutResponse(): void
    {
        $apiContext = new ApiContext(
            new CredentialsOAuthContext('client-id', 'client-secret'),
            true,
        );

        $request = new Request('POST', Constants::BASEURL_SANDBOX . '/v2/orders/id');

        $body = ['id' => 'some-order-id'];

        $context = new TestRequestContext(
            OrderGateway::class,
            'getOrder',
            $request,
            $apiContext,
            $body,
        );

        static::expectException(\RuntimeException::class);
        static::expectExceptionMessage(TestRequestContext::class . '::getResponse is only available after a response has been generate');

        $context->getResponse();
    }

    public function testWithoutBodyAndResponseClass(): void
    {
        $apiContext = new ApiContext(
            new CredentialsOAuthContext('client-id', 'client-secret'),
            true,
        );

        $request = new Request('POST', Constants::BASEURL_SANDBOX . '/v2/orders/id');

        $context = new TestRequestContext(
            OrderGateway::class,
            'getOrder',
            $request,
            $apiContext,
            null,
        );

        static::assertSame($request, $context->getRequest());
        static::assertNull($context->getRequestBody());
        static::assertSame($apiContext, $context->getContext());
    }
}
