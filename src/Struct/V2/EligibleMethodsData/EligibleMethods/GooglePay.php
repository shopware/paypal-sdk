<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\EligibleMethodsData\EligibleMethods;

use OpenApi\Attributes as OA;

/**
 * @experimental
 *
 * @phpstan-type GooglePayConfig array<string, mixed>&array{
 *   eligible: bool,
 *   merchant_country: string,
 *   api_version: int,
 *   api_version_minor: int,
 *   allowed_payment_methods: list<array<string, mixed>>,
 *   merchant_info: array<string, mixed>,
 * }
 */
#[OA\Schema(schema: 'paypal_v2_eligible_methods_data_eligible_methods_google_pay')]
class GooglePay extends Paypal
{
    /** @var GooglePayConfig */
    #[OA\Property(type: 'array', items: new OA\Items(type: 'mixed'))]
    protected array $config;

    /**
     * @return GooglePayConfig
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @param GooglePayConfig $config
     */
    public function setConfig(array $config): void
    {
        $this->config = $config;
    }
}
