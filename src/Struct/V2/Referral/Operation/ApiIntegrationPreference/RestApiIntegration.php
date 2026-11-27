<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Referral\Operation\ApiIntegrationPreference;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Pattern;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Referral\Operation\ApiIntegrationPreference\RestApiIntegration\ThirdPartyDetails;

#[OA\Schema(schema: 'paypal_v2_referral_operation_api_integration_preference_rest_api_integration')]
class RestApiIntegration extends Struct
{
    public const string INTEGRATION_METHOD_TYPE_PAYPAL = 'PAYPAL';
    public const string INTEGRATION_METHOD_TYPE_SDK = 'SDK';
    public const string INTEGRATION_TYPE_THIRD_PARTY = 'THIRD_PARTY';

    #[OA\Property(type: 'string', minLength: 1, maxLength: 255, pattern: Pattern::UPPER_ALNUM_UNDERSCORE_HYPHEN)]
    protected ?string $integrationMethod = null;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 255, pattern: Pattern::UPPER_ALNUM_UNDERSCORE_HYPHEN)]
    protected string $integrationType;

    #[OA\Property(ref: ThirdPartyDetails::class)]
    protected ThirdPartyDetails $thirdPartyDetails;

    public function getIntegrationMethod(): ?string
    {
        return $this->integrationMethod;
    }

    public function setIntegrationMethod(?string $integrationMethod): void
    {
        $this->integrationMethod = $integrationMethod;
    }

    public function getIntegrationType(): string
    {
        return $this->integrationType;
    }

    public function setIntegrationType(string $integrationType): void
    {
        $this->integrationType = $integrationType;
    }

    public function getThirdPartyDetails(): ThirdPartyDetails
    {
        return $this->thirdPartyDetails;
    }

    public function setThirdPartyDetails(ThirdPartyDetails $thirdPartyDetails): void
    {
        $this->thirdPartyDetails = $thirdPartyDetails;
    }
}
