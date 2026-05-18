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
 * @phpstan-type ApplePayConfig array<string, mixed>&array{
 *   eligible: bool,
 *   merchant_country: string,
 *   supported_networks: list<string>,
 *   merchant_capabilities: list<string>,
 *   token_notification_url: string,
 * }
 */
#[OA\Schema(schema: 'paypal_v2_eligible_methods_data_eligible_methods_apple_pay')]
class ApplePay extends Paypal
{
    /** @var ApplePayConfig */
    #[OA\Property(type: 'array', items: new OA\Items(type: 'mixed'))]
    protected array $config;

    /**
     * @return ApplePayConfig
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @param ApplePayConfig $config
     */
    public function setConfig(array $config): void
    {
        $this->config = $config;
    }
}
