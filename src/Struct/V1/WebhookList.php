<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Webhook\WebhookCollection;

#[OA\Schema(schema: 'paypal_v1_webhook_list')]
class WebhookList extends Struct
{
    #[OA\Property(type: 'array', items: new OA\Items(ref: Webhook::class))]
    protected WebhookCollection $webhooks;

    public function getWebhooks(): WebhookCollection
    {
        return $this->webhooks;
    }

    public function setWebhooks(WebhookCollection $webhooks): void
    {
        $this->webhooks = $webhooks;
    }
}
