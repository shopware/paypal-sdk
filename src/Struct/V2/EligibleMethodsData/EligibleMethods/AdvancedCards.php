<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\EligibleMethodsData\EligibleMethods;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\EligibleMethodsData\EligibleMethods\AdvancedCards\Vendor;
use Shopware\PayPalSDK\Struct\V2\EligibleMethodsData\EligibleMethods\AdvancedCards\VendorCollection;

/**
 * @experimental
 */
#[OA\Schema(schema: 'paypal_v2_eligible_methods_data_eligible_methods_advanced_cards')]
class AdvancedCards extends Struct
{
    #[OA\Property(type: 'boolean')]
    protected bool $supportsInstallements;

    #[OA\Property(type: 'boolean')]
    protected bool $cobrandedEnabled;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Vendor::class))]
    protected VendorCollection $vendors;

    public function isSupportsInstallements(): bool
    {
        return $this->supportsInstallements;
    }

    public function setSupportsInstallements(bool $supportsInstallements): void
    {
        $this->supportsInstallements = $supportsInstallements;
    }

    public function isCobrandedEnabled(): bool
    {
        return $this->cobrandedEnabled;
    }

    public function setCobrandedEnabled(bool $cobrandedEnabled): void
    {
        $this->cobrandedEnabled = $cobrandedEnabled;
    }

    public function getVendors(): VendorCollection
    {
        return $this->vendors;
    }

    public function setVendors(VendorCollection $vendors): void
    {
        $this->vendors = $vendors;
    }
}
