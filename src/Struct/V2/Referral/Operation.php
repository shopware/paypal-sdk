<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Referral;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Referral\Operation\ApiIntegrationPreference;

#[OA\Schema(schema: 'paypal_v2_referral_operation')]
class Operation extends Struct
{
    public const OPERATION_TYPE_API_INTEGRATION = 'API_INTEGRATION';

    #[OA\Property(type: 'string', default: self::OPERATION_TYPE_API_INTEGRATION)]
    protected string $operation = self::OPERATION_TYPE_API_INTEGRATION;

    #[OA\Property(ref: ApiIntegrationPreference::class)]
    protected ApiIntegrationPreference $apiIntegrationPreference;

    public function getOperation(): string
    {
        return $this->operation;
    }

    public function setOperation(string $operation): void
    {
        $this->operation = $operation;
    }

    public function getApiIntegrationPreference(): ApiIntegrationPreference
    {
        return $this->apiIntegrationPreference;
    }

    public function setApiIntegrationPreference(ApiIntegrationPreference $apiIntegrationPreference): void
    {
        $this->apiIntegrationPreference = $apiIntegrationPreference;
    }
}
