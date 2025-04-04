<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Contract\Gateway;

use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;
use Shopware\PayPalSDK\Struct\V1\PatchCollection;
use Shopware\PayPalSDK\Struct\V1\Webhook;

interface WebhookGatewayInterface
{
    public const GATEWAY_URL = '/v1/notifications/webhooks';

    public function createWebhook(Webhook $webhook, ApiContextInterface $context): Webhook;

    public function getWebhook(string $webhookId, ApiContextInterface $context): Webhook;

    public function updateWebhook(string $webhookId, PatchCollection $patches, ApiContextInterface $context): void;

    public function deleteWebhook(string $webhookId, ApiContextInterface $context): void;
}
