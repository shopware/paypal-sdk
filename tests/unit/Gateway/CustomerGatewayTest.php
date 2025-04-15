<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Gateway;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Context\ApiContext;
use Shopware\PayPalSDK\Context\CredentialsOAuthContext;
use Shopware\PayPalSDK\Gateway\CustomerGateway;
use Shopware\PayPalSDK\Struct\V1\Disputes;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item;
use Shopware\PayPalSDK\Struct\V1\MerchantIntegrations;
use Shopware\PayPalSDK\Struct\V2\Referral;
use Shopware\PayPalSDK\Test\Gateway\TestGateways;
use Shopware\PayPalSDK\Test\Request\TestClient;

/**
 * @internal
 */
#[CoversClass(CustomerGateway::class)]
class CustomerGatewayTest extends TestCase
{
    protected TestClient $client;

    protected TestGateways $gateways;

    protected function setUp(): void
    {
        $this->client = new TestClient();
        $this->gateways = new TestGateways($this->client);
    }

    public function testGetMerchantIntegrations(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new MerchantIntegrations())->assign(['id' => 'merchant-id']);

        $this->gateways->setCachedToken($context);
        $this->client->add(new Response(body: \json_encode($body, \JSON_THROW_ON_ERROR)));

        $response = $this->gateways->customerGateway()->getMerchantIntegrations('partnerId', $context);
        static::assertEquals($body, $response);

        $last = $this->client->last();
        static::assertNotNull($last);
        static::assertSame('GET', $last->getRequest()->getMethod());
        static::assertSame('/v1/customer/partners/partnerId/merchant-integrations/merchant-id', $last->getRequest()->getUri()->getPath());
    }

    public function testGetDisputes(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Disputes())->assign(['items' => [['dispute_id' => 'some-dispute-id']]]);

        $this->gateways->setCachedToken($context);
        $this->client->add(new Response(body: \json_encode($body, \JSON_THROW_ON_ERROR)));

        $response = $this->gateways->customerGateway()->getDisputes($context);
        static::assertEquals($body, $response);

        $last = $this->client->last();
        static::assertNotNull($last);
        static::assertSame('GET', $last->getRequest()->getMethod());
        static::assertSame('/v1/customer/disputes', $last->getRequest()->getUri()->getPath());
    }

    public function testGetDispute(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Item())->assign(['dispute_id' => 'some-dispute-id']);

        $this->gateways->setCachedToken($context);
        $this->client->add(new Response(body: \json_encode($body, \JSON_THROW_ON_ERROR)));

        $response = $this->gateways->customerGateway()->getDispute('some-dispute-id', $context);
        static::assertEquals($body, $response);

        $last = $this->client->last();
        static::assertNotNull($last);
        static::assertSame('GET', $last->getRequest()->getMethod());
        static::assertSame('/v1/customer/disputes/some-dispute-id', $last->getRequest()->getUri()->getPath());
    }

    public function testGetCredentials(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = ['client_id' => 'some-client-id', 'client_secret' => 'some-client-secret', 'payer_id' => 'some-payer-id'];

        $this->gateways->setCachedToken($context);
        $this->client->add(new Response(body: \json_encode($body, \JSON_THROW_ON_ERROR)));

        $response = $this->gateways->customerGateway()->getCredentials('partnerId', $context);
        static::assertSame($body['client_id'], $response->getClientId());
        static::assertSame($body['client_secret'], $response->getClientSecret());
        static::assertSame($body['payer_id'], $response->getPayerId());

        $last = $this->client->last();
        static::assertNotNull($last);
        static::assertSame('GET', $last->getRequest()->getMethod());
        static::assertSame('/v1/customer/partners/partnerId/merchant-integrations/credentials', $last->getRequest()->getUri()->getPath());
    }

    public function testCreatePartnerReferral(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Referral())->assign(['tracking_id' => 'tracking-id']);

        $this->gateways->setCachedToken($context);
        $this->client->add(new Response(body: \json_encode($body, \JSON_THROW_ON_ERROR)));

        $response = $this->gateways->customerGateway()->createPartnerReferral($body, $context);
        static::assertEquals($body, $response);

        $last = $this->client->last();
        static::assertNotNull($last);
        static::assertSame('POST', $last->getRequest()->getMethod());
        static::assertSame('/v2/customer/partner-referrals', $last->getRequest()->getUri()->getPath());
        static::assertSame(\json_encode($body), (string) $last->getRequest()->getBody());
    }
}
