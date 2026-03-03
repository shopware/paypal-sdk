<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Webhook;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'paypal_v1_webhook_application_context')]
class ApplicationContext
{
    #[OA\Property(type: 'string')]
    protected string $eventContext;

    /** @var list<string> */
    #[OA\Property(type: 'array', items: new OA\Items(type: 'string'))]
    protected array $eventTargets = [];

    public function getEventContext(): string
    {
        return $this->eventContext;
    }

    public function setEventContext(string $eventContext): void
    {
        $this->eventContext = $eventContext;
    }

    /**
     * @return list<string>
     */
    public function getEventTargets(): array
    {
        return $this->eventTargets;
    }

    /**
     * @param list<string> $eventTargets
     */
    public function setEventTargets(array $eventTargets): void
    {
        $this->eventTargets = $eventTargets;
    }
}
