<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Referral;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

/**
 * @experimental
 */
class MixedItem extends Struct
{
    #[OA\Property(type: 'string')]
    protected string $itemId;

    #[OA\Property(type: 'string')]
    protected string $currency;

    public function getItemId(): string
    {
        return $this->itemId;
    }

    public function setItemId(string $itemId): void
    {
        $this->itemId = $itemId;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }
}
