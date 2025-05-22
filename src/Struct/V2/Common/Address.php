<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Common;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v2_common_address')]
class Address extends Struct
{
    /**
     * The first line of the address. For example, number or street. For example, 173 Drury Lane.
     * Required for data entry and compliance and risk checks. Must contain the full address.
     */
    #[OA\Property(type: 'string', maxLength: 300, nullable: true)]
    protected ?string $addressLine1 = null;

    /**
     * The second line of the address. For example, suite or apartment number.
     */
    #[OA\Property(type: 'string', maxLength: 300, nullable: true)]
    protected ?string $addressLine2 = null;

    /**
     * A city, town, or village. Smaller than $adminArea1
     */
    #[OA\Property(type: 'string', maxLength: 120, nullable: true)]
    protected ?string $adminArea2 = null;

    /**
     * The highest level sub-division in a country, which is usually a province, state, or ISO-3166-2 subdivision.
     * Format for postal delivery. For example, CA and not California.
     */
    #[OA\Property(type: 'string', maxLength: 300, nullable: true)]
    protected ?string $adminArea1 = null;

    #[OA\Property(type: 'string', maxLength: 60, nullable: true)]
    protected ?string $postalCode = null;

    #[OA\Property(type: 'string', maxLength: 2, minLength: 2)]
    protected string $countryCode;

    public function getAddressLine1(): ?string
    {
        return $this->addressLine1;
    }

    public function setAddressLine1(?string $addressLine1): void
    {
        $this->addressLine1 = $addressLine1;
    }

    public function getAddressLine2(): ?string
    {
        return $this->addressLine2;
    }

    public function setAddressLine2(?string $addressLine2): void
    {
        $this->addressLine2 = $addressLine2;
    }

    public function getAdminArea2(): ?string
    {
        return $this->adminArea2;
    }

    public function setAdminArea2(?string $adminArea2): void
    {
        $this->adminArea2 = $adminArea2;
    }

    public function getAdminArea1(): ?string
    {
        return $this->adminArea1;
    }

    public function setAdminArea1(?string $adminArea1): void
    {
        $this->adminArea1 = $adminArea1;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    public function jsonSerialize(): array
    {
        return \array_filter(parent::jsonSerialize());
    }
}
