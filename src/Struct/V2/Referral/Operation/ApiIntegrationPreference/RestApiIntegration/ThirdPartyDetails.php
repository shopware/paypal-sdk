<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Referral\Operation\ApiIntegrationPreference\RestApiIntegration;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v2_referral_operation_api_integration_preference_rest_api_integration_third_party_details')]
class ThirdPartyDetails extends Struct
{
    public const FEATURE_TYPE_PAYMENT = 'PAYMENT';
    public const FEATURE_TYPE_REFUND = 'REFUND';
    public const FEATURE_TYPE_ACCESS_MERCHANT_INFORMATION = 'ACCESS_MERCHANT_INFORMATION';
    public const FEATURE_TYPE_ADVANCED_TRANSACTIONS_SEARCH = 'ADVANCED_TRANSACTIONS_SEARCH';
    public const FEATURE_TYPE_UPDATE_SELLER_DISPUTE = 'UPDATE_SELLER_DISPUTE';
    public const FEATURE_TYPE_READ_SELLER_DISPUTE = 'READ_SELLER_DISPUTE';
    public const FEATURE_TYPE_DELAY_FUNDS_DISBURSEMENT = 'DELAY_FUNDS_DISBURSEMENT';
    public const FEATURE_TYPE_TRACKING_SHIPMENT_READWRITE = 'TRACKING_SHIPMENT_READWRITE';
    public const FEATURE_TYPE_VAULT = 'VAULT';
    public const FEATURE_TYPE_BILLING_AGREEMENT = 'BILLING_AGREEMENT';
    public const SIGNUP_MODE_VERIFY_WITH_PAYPAL = 'VERIFY_WITH_PAYPAL';

    /**
     * @var string[]
     */
    #[OA\Property(type: 'array', items: new OA\Items(type: 'string'))]
    protected array $features = [];

    #[OA\Property(type: 'string')]
    protected string $signupMode;

    #[OA\Property(type: 'string')]
    protected string $organization;

    /**
     * @return string[]
     */
    public function getFeatures(): array
    {
        return $this->features;
    }

    /**
     * @param string[] $features
     */
    public function setFeatures(array $features): void
    {
        $this->features = $features;
    }

    public function addFeature(string $feature): void
    {
        $this->features[] = $feature;
    }

    public function getSignupMode(): string
    {
        return $this->signupMode;
    }

    public function setSignupMode(string $signupMode): void
    {
        $this->signupMode = $signupMode;
    }

    public function getOrganization(): string
    {
        return $this->organization;
    }

    public function setOrganization(string $organization): void
    {
        $this->organization = $organization;
    }
}
