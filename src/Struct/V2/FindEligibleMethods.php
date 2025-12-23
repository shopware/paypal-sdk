<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\FindEligibleMethods\Customer;
use Shopware\PayPalSDK\Struct\V2\FindEligibleMethods\Preferences;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnitCollection;

/**
 * @experimental
 */
#[OA\Schema(schema: 'paypal_v2_find_eligible_methods')]
class FindEligibleMethods extends Struct
{
    #[OA\Property(ref: Customer::class)]
    protected ?Customer $customer = null;

    #[OA\Property(ref: Preferences::class)]
    protected Preferences $preferences;

    /**
     * Does not have to be a full purchase unit.
     * `[{"amount":{"currency_code":"<iso-4217-code>"},"payee":{"merchant_id":"<merchant-id>"}}]` is enough.
     */
    #[OA\Property(type: 'array', items: new OA\Items(ref: PurchaseUnit::class))]
    protected PurchaseUnitCollection $purchaseUnits;

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): void
    {
        $this->customer = $customer;
    }

    public function getPreferences(): Preferences
    {
        return $this->preferences;
    }

    public function setPreferences(Preferences $preferences): void
    {
        $this->preferences = $preferences;
    }

    public function getPurchaseUnits(): PurchaseUnitCollection
    {
        return $this->purchaseUnits;
    }

    public function setPurchaseUnits(PurchaseUnitCollection $purchaseUnits): void
    {
        $this->purchaseUnits = $purchaseUnits;
    }

    public function jsonSerialize(): array
    {
        return \array_filter([
            ...parent::jsonSerialize(),
            'purchase_units' => \array_map(\array_filter(...), $this->purchaseUnits->jsonSerialize()),
        ]);
    }
}
