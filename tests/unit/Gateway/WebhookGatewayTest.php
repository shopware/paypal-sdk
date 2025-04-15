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
use Shopware\PayPalSDK\Gateway\WebhookGateway;
use Shopware\PayPalSDK\Struct\V1\PatchCollection;
use Shopware\PayPalSDK\Struct\V1\Webhook;
use Shopware\PayPalSDK\Test\Gateway\TestGateways;
use Shopware\PayPalSDK\Test\Request\TestClient;

/**
 * @internal
 */
#[CoversClass(WebhookGateway::class)]
class WebhookGatewayTest extends TestCase
{
    protected TestClient $client;

    protected TestGateways $gateways;

    protected function setUp(): void
    {
        $this->client = new TestClient();
        $this->gateways = new TestGateways($this->client);
    }

    public function testCreateWebhook(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Webhook())->assign(['id' => 'webhook-id']);

        $this->gateways->setCachedToken($context);
        $this->client->add(new Response(body: \json_encode($body, \JSON_THROW_ON_ERROR)));

        $response = $this->gateways->webhookGateway()->createWebhook($body, $context);
        static::assertEquals($body, $response);

        $last = $this->client->last();
        static::assertNotNull($last);
        static::assertSame('POST', $last->getRequest()->getMethod());
        static::assertSame('/v1/notifications/webhooks', $last->getRequest()->getUri()->getPath());
        static::assertSame(\json_encode($body), (string) $last->getRequest()->getBody());
    }

    public function testGetWebhook(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Webhook())->assign(['id' => 'webhook-id']);

        $this->gateways->setCachedToken($context);
        $this->client->add(new Response(body: \json_encode($body, \JSON_THROW_ON_ERROR)));

        $response = $this->gateways->webhookGateway()->getWebhook('webhookId', $context);

        $last = $this->client->last();
        static::assertNotNull($last);
        static::assertEquals($body, $response);
        static::assertSame('GET', $last->getRequest()->getMethod());
        static::assertSame('/v1/notifications/webhooks/webhookId', $last->getRequest()->getUri()->getPath());
    }

    public function testUpdateWebhook(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = PatchCollection::createFromAssociative([[
            'op' => 'replace',
            'path' => '/some/path',
        ]]);

        $this->gateways->setCachedToken($context);
        $this->client->add(new Response());

        $this->gateways->webhookGateway()->updateWebhook('webhookId', $body, $context);

        $last = $this->client->last();
        static::assertNotNull($last);
        static::assertSame('PATCH', $last->getRequest()->getMethod());
        static::assertSame('/v1/notifications/webhooks/webhookId', $last->getRequest()->getUri()->getPath());
        static::assertSame(\json_encode($body), (string) $last->getRequest()->getBody());
    }

    public function testDeleteWebhook(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');

        $this->gateways->setCachedToken($context);
        $this->client->add(new Response());

        $this->gateways->webhookGateway()->deleteWebhook('webhookId', $context);

        $last = $this->client->last();
        static::assertNotNull($last);
        static::assertSame('DELETE', $last->getRequest()->getMethod());
        static::assertSame('/v1/notifications/webhooks/webhookId', $last->getRequest()->getUri()->getPath());
    }
}
