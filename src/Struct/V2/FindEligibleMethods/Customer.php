<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\FindEligibleMethods;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\FindEligibleMethods\Customer\Channel;

/**
 * @experimental
 */
#[OA\Schema(schema: 'paypal_v2_find_eligible_methods_customer')]
class Customer extends Struct
{
    /**
     * ISO 3166-1 alpha-2 country code
     */
    #[OA\Property(type: 'string', maxLength: 2, minLength: 2)]
    protected string $countryCode;

    #[OA\Property(ref: Channel::class)]
    protected ?Channel $channel = null;

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    public function getChannel(): ?Channel
    {
        return $this->channel;
    }

    public function setChannel(?Channel $channel): void
    {
        $this->channel = $channel;
    }

    public function jsonSerialize(): array
    {
        return \array_filter(parent::jsonSerialize());
    }
}
