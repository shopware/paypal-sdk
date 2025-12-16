<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\FindEligibleMethods\Customer;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

/**
 * @experimental
 */
#[OA\Schema(schema: 'paypal_v2_find_eligible_methods_customer_channel')]
class Channel extends Struct
{
    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $browserType = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $clientOs = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $deviceType = null;

    public function getBrowserType(): ?string
    {
        return $this->browserType;
    }

    public function setBrowserType(?string $browserType): void
    {
        $this->browserType = $browserType;
    }

    public function getClientOs(): ?string
    {
        return $this->clientOs;
    }

    public function setClientOs(?string $clientOs): void
    {
        $this->clientOs = $clientOs;
    }

    public function getDeviceType(): ?string
    {
        return $this->deviceType;
    }

    public function setDeviceType(?string $deviceType): void
    {
        $this->deviceType = $deviceType;
    }

    public function jsonSerialize(): array
    {
        return \array_filter(parent::jsonSerialize());
    }
}
