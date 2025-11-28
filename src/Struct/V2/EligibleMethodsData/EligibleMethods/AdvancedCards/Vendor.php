<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\EligibleMethodsData\EligibleMethods\AdvancedCards;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\V2\EligibleMethodsData\EligibleMethods\Paypal;

/**
 * @experimental
 */
#[OA\Schema(schema: 'paypal_v2_eligible_methods_data_eligible_methods_advanced_cards_vendor')]
class Vendor extends Paypal
{
    #[OA\Property(type: 'boolean')]
    protected bool $eligible;

    #[OA\Property(type: 'string')]
    protected string $network;

    #[OA\Property(type: 'boolean')]
    protected bool $branded;

    public function isEligible(): bool
    {
        return $this->eligible;
    }

    public function setEligible(bool $eligible): void
    {
        $this->eligible = $eligible;
    }

    public function getNetwork(): string
    {
        return $this->network;
    }

    public function setNetwork(string $network): void
    {
        $this->network = $network;
    }

    public function isBranded(): bool
    {
        return $this->branded;
    }

    public function setBranded(bool $branded): void
    {
        $this->branded = $branded;
    }
}
