<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\SupplementaryData;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\SupplementaryData\Card\Level2;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\SupplementaryData\Card\Level3;

#[OA\Schema(schema: 'paypal_v2_order_purchase_unit_supplementary_data_card')]
class Card extends Struct
{
    #[OA\Property(ref: Level2::class, nullable: true)]
    protected ?Level2 $level2;

    #[OA\Property(ref: Level3::class, nullable: true)]
    protected ?Level3 $level3;

    public function getLevel2(): ?Level2
    {
        return $this->level2 ?? null;
    }

    public function setLevel2(?Level2 $level2): void
    {
        $this->level2 = $level2;
    }

    public function getLevel3(): ?Level3
    {
        return $this->level3 ?? null;
    }

    public function setLevel3(?Level3 $level3): void
    {
        $this->level3 = $level3;
    }
}
