<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Subscription\Subscriber\ShippingAddress;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

/**
 * @experimental
 */
#[OA\Schema(schema: 'paypal_v1_subscription_subscriber_shipping_address_address')]
class Address extends Struct
{
    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $addressLine1 = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $addressLine2 = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $adminArea1 = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $adminArea2 = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $postalCode = null;

    #[OA\Property(type: 'string')]
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
        $this->countryCode = $countryCode;
    }
}
