<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Gateway;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use Shopware\PayPalSDK\Context\ApiContext;
use Shopware\PayPalSDK\Context\CredentialsOAuthContext;
use Shopware\PayPalSDK\Gateway\CustomerGateway;
use Shopware\PayPalSDK\Struct\V1\Disputes;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item;
use Shopware\PayPalSDK\Struct\V1\MerchantIntegrations;
use Shopware\PayPalSDK\Struct\V2\Referral;

/**
 * @internal
 *
 * @extends AbstractGatewayTestCase<CustomerGateway>
 */
#[CoversClass(CustomerGateway::class)]
class CustomerGatewayTest extends AbstractGatewayTestCase
{
    protected CustomerGateway $gateway;

    protected function setUp(): void
    {
        $this->gateway = new CustomerGateway($this->client, $this->tokenGateway);
    }

    protected function gatewayClass(): string
    {
        return CustomerGateway::class;
    }

    public function testGetMerchantIntegrations(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new MerchantIntegrations())->assign(['id' => 'merchant-id']);

        $this->setCachedToken($context, $this->getValidToken());
        $this->addStructResponse($body);

        $response = $this->gateway->getMerchantIntegrations('partnerId', $context);
        static::assertEquals($body, $response);
        static::assertSame('GET', $this->getLast()->getMethod());
        static::assertSame('/v1/customer/partners/partnerId/merchant-integrations/merchant-id', $this->getLast()->getUri()->getPath());
    }

    public function testGetDisputes(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Disputes())->assign(['items' => [['dispute_id' => 'some-dispute-id']]]);

        $this->setCachedToken($context, $this->getValidToken());
        $this->addStructResponse($body);

        $response = $this->gateway->getDisputes($context);
        static::assertEquals($body, $response);
        static::assertSame('GET', $this->getLast()->getMethod());
        static::assertSame('/v1/customer/disputes', $this->getLast()->getUri()->getPath());
    }

    public function testGetDispute(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Item())->assign(['dispute_id' => 'some-dispute-id']);

        $this->setCachedToken($context, $this->getValidToken());
        $this->addStructResponse($body);

        $response = $this->gateway->getDispute('some-dispute-id', $context);
        static::assertEquals($body, $response);
        static::assertSame('GET', $this->getLast()->getMethod());
        static::assertSame('/v1/customer/disputes/some-dispute-id', $this->getLast()->getUri()->getPath());
    }

    public function testGetCredentials(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = ['client_id' => 'some-client-id', 'client_secret' => 'some-client-secret', 'payer_id' => 'some-payer-id'];
        $json = \json_encode($body, \JSON_THROW_ON_ERROR);

        $this->setCachedToken($context, $this->getValidToken());
        $this->handler->append(new Response(body: $json));

        $response = $this->gateway->getCredentials('partnerId', $context);
        static::assertSame($body['client_id'], $response->getClientId());
        static::assertSame($body['client_secret'], $response->getClientSecret());
        static::assertSame($body['payer_id'], $response->getPayerId());
        static::assertSame('GET', $this->getLast()->getMethod());
        static::assertSame('/v1/customer/partners/partnerId/merchant-integrations/credentials', $this->getLast()->getUri()->getPath());
    }

    public function testCreatePartnerReferral(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Referral())->assign(['tracking_id' => 'tracking-id']);

        $this->setCachedToken($context, $this->getValidToken());
        $this->addStructResponse($body);

        $response = $this->gateway->createPartnerReferral($body, $context);
        static::assertEquals($body, $response);
        static::assertSame('POST', $this->getLast()->getMethod());
        static::assertSame('/v2/customer/partner-referrals', $this->getLast()->getUri()->getPath());
        static::assertSame(\json_encode($body), (string) $this->getLast()->getBody());
    }
}
