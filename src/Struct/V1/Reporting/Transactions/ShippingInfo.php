<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Reporting\Transactions;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Common\Address;

#[OA\Schema(schema: 'paypal_v1_reporting_transactions_shipping_info')]
class ShippingInfo extends Struct
{
    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $name = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $method = null;

    #[OA\Property(ref: Address::class, nullable: true)]
    protected ?Address $address = null;

    #[OA\Property(ref: Address::class, nullable: true)]
    protected ?Address $secondaryShippingAddress = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(?string $method): void
    {
        $this->method = $method;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): void
    {
        $this->address = $address;
    }

    public function getSecondaryShippingAddress(): ?Address
    {
        return $this->secondaryShippingAddress;
    }

    public function setSecondaryShippingAddress(?Address $secondaryShippingAddress): void
    {
        $this->secondaryShippingAddress = $secondaryShippingAddress;
    }
}
