<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Referral;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v2_referral_partner_config_override')]
class PartnerConfigOverride extends Struct
{
    #[OA\Property(type: 'string')]
    protected string $returnUrl;

    #[OA\Property(type: 'string')]
    protected string $partnerLogoUrl = 'https://assets.shopware.com/media/logos/shopware_logo_blue.svg';

    public function getReturnUrl(): string
    {
        return $this->returnUrl;
    }

    public function setReturnUrl(string $returnUrl): void
    {
        $this->returnUrl = $returnUrl;
    }

    public function getPartnerLogoUrl(): string
    {
        return $this->partnerLogoUrl;
    }

    public function setPartnerLogoUrl(string $partnerLogoUrl): void
    {
        $this->partnerLogoUrl = $partnerLogoUrl;
    }
}
