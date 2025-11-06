<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Gateway;

use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;
use Shopware\PayPalSDK\Struct\V1\PatchCollection;
use Shopware\PayPalSDK\Struct\V1\Webhook;
use Shopware\PayPalSDK\Struct\V1\WebhookList;

class WebhookGateway extends AbstractGateway
{
    public const GATEWAY_URL = '/v1/notifications/webhooks';

    public function createWebhook(Webhook $webhook, ApiContextInterface $context): Webhook
    {
        return $this->request(
            'POST',
            self::GATEWAY_URL,
            $webhook,
            Webhook::class,
            $context
        );
    }

    public function getWebhook(string $webhookId, ApiContextInterface $context): Webhook
    {
        return $this->request(
            'GET',
            self::GATEWAY_URL . '/' . $webhookId,
            null,
            Webhook::class,
            $context
        );
    }

    public function getWebhookList(ApiContextInterface $context): WebhookList
    {
        return $this->request(
            'GET',
            self::GATEWAY_URL,
            null,
            WebhookList::class,
            $context
        );
    }

    public function updateWebhook(string $webhookId, PatchCollection $patches, ApiContextInterface $context): void
    {
        $this->request(
            'PATCH',
            self::GATEWAY_URL . '/' . $webhookId,
            $patches,
            null,
            $context
        );
    }

    public function deleteWebhook(string $webhookId, ApiContextInterface $context): void
    {
        $this->request(
            'DELETE',
            self::GATEWAY_URL . '/' . $webhookId,
            null,
            null,
            $context
        );
    }
}
