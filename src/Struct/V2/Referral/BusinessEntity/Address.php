<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Referral\BusinessEntity;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v2_referral_business_entity_address')]
class Address extends Struct
{
    public const TYPE_WORK = 'WORK';

    #[OA\Property(type: 'string')]
    protected string $countryCode;

    #[OA\Property(type: 'string', default: self::TYPE_WORK)]
    protected string $type = self::TYPE_WORK;

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
