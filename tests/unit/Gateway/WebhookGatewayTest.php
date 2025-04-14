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
use Shopware\PayPalSDK\Gateway\WebhookGateway;
use Shopware\PayPalSDK\Struct\V1\PatchCollection;
use Shopware\PayPalSDK\Struct\V1\Webhook;

/**
 * @internal
 *
 * @extends AbstractGatewayTestCase<WebhookGateway>
 */
#[CoversClass(WebhookGateway::class)]
class WebhookGatewayTest extends AbstractGatewayTestCase
{
    protected WebhookGateway $gateway;

    protected function setUp(): void
    {
        $this->gateway = new WebhookGateway($this->client, $this->tokenGateway);
    }

    protected function gatewayClass(): string
    {
        return WebhookGateway::class;
    }

    public function testCreateWebhook(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Webhook())->assign(['id' => 'webhook-id']);

        $this->setCachedToken($context, $this->getValidToken());
        $this->addStructResponse($body);

        $response = $this->gateway->createWebhook($body, $context);
        static::assertEquals($body, $response);
        static::assertSame('POST', $this->getLast()->getMethod());
        static::assertSame('/v1/notifications/webhooks', $this->getLast()->getUri()->getPath());
        static::assertSame(\json_encode($body), (string) $this->getLast()->getBody());
    }

    public function testGetWebhook(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Webhook())->assign(['id' => 'webhook-id']);

        $this->setCachedToken($context, $this->getValidToken());
        $this->addStructResponse($body);

        $response = $this->gateway->getWebhook('webhookId', $context);
        static::assertEquals($body, $response);
        static::assertSame('GET', $this->getLast()->getMethod());
        static::assertSame('/v1/notifications/webhooks/webhookId', $this->getLast()->getUri()->getPath());
    }

    public function testUpdateWebhook(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = PatchCollection::createFromAssociative([[
            'op' => 'replace',
            'path' => '/some/path',
        ]]);

        $this->setCachedToken($context, $this->getValidToken());
        $this->handler->append(new Response());

        $this->gateway->updateWebhook('webhookId', $body, $context);
        static::assertSame('PATCH', $this->getLast()->getMethod());
        static::assertSame('/v1/notifications/webhooks/webhookId', $this->getLast()->getUri()->getPath());
        static::assertSame(\json_encode($body), (string) $this->getLast()->getBody());
    }

    public function testDeleteWebhook(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');

        $this->setCachedToken($context, $this->getValidToken());
        $this->handler->append(new Response());

        $this->gateway->deleteWebhook('webhookId', $context);
        static::assertSame('DELETE', $this->getLast()->getMethod());
        static::assertSame('/v1/notifications/webhooks/webhookId', $this->getLast()->getUri()->getPath());
    }
}
