<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(
    schema: 'paypal_agentic_commerce_v1_address',
    required: ['countryCode']
)]
class Address extends Struct
{
    /**
     * The first line of the address, such as number and street, for example, 173 Drury Lane.
     * Needed for data entry, and Compliance and Risk checks. This field needs to pass the full address.
     *
     * minLength: 0
     * maxLength: 300
     * pattern: ^.*$
     */
    #[OA\Property(
        type: 'string',
        maxLength: 300,
        minLength: 0,
    )]
    protected ?string $addressline1 = null;

    #[OA\Property(
        type: 'string',
        maxLength: 300,
        minLength: 0,
    )]
    protected ?string $addressline2 = null;

    /**
     * The highest-level sub-division in a country, which is usually a province, state, or ISO-3166-2 subdivision.
     * This data is formatted for postal delivery, for example, CA and not California. Value, by country, is UK.
     * A county. US. A state. Canada. A province. Japan. A prefecture. Switzerland. A kanton.
     *
     * minLength: 0
     * maxLength: 300
     * pattern: ^.*$
     */
    #[OA\Property(
        type: 'string',
        maxLength: 300,
        minLength: 0,
    )]
    protected ?string $adminArea1 = null;

    /**
     * A city, town, or village. Smaller than admin_area_level_1.
     *
     * minLength: 0
     * maxLength: 120
     * pattern: ^.*$
     * example: San Jose
     */
    #[OA\Property(
        type: 'string',
        maxLength: 120,
        minLength: 0,
    )]
    protected ?string $adminArea2 = null;

    /**
     * The postal code, which is the ZIP code or equivalent.
     * Typically required for countries with a postal code or an equivalent. See postal code.
     *
     * minLength: 0
     * maxLength: 60
     * pattern: ^.*$
     */
    #[OA\Property(
        type: 'string',
        maxLength: 60,
        minLength: 0,
    )]
    protected ?string $postalCode = null;

    /**
     * The 2-character ISO 3166-1 alpha-2 country code
     */
    #[OA\Property(
        type: 'string',
        maxLength: 2,
        minLength: 2,
        pattern: '^[A-Z]{2}$'
    )]
    protected string $countryCode;

    public function getAddressline1(): ?string
    {
        return $this->addressline1;
    }

    public function setAddressline1(?string $addressline1): void
    {
        $this->addressline1 = $addressline1;
    }

    public function getAddressline2(): ?string
    {
        return $this->addressline2;
    }

    public function setAddressline2(?string $addressline2): void
    {
        $this->addressline2 = $addressline2;
    }

    public function getAdminArea1(): ?string
    {
        return $this->adminArea1;
    }

    public function setAdminArea1(?string $adminArea1): void
    {
        $this->adminArea1 = $adminArea1;
    }

    public function getAdminArea2(): ?string
    {
        return $this->adminArea2;
    }

    public function setAdminArea2(?string $adminArea2): void
    {
        $this->adminArea2 = $adminArea2;
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
        if (!preg_match('/^[A-Z]{2}$/', $countryCode)) {
            throw new \InvalidArgumentException('Country code must be alphanumeric');
        }

        $this->countryCode = $countryCode;
    }
}
