<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Contract\Gateway;

use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;
use Shopware\PayPalSDK\Struct\V1\Disputes;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item as DisputeItem;
use Shopware\PayPalSDK\Struct\V1\MerchantIntegrations;
use Shopware\PayPalSDK\Struct\V1\MerchantIntegrations\Credentials;

interface CustomerGatewayInterface
{
    public const GATEWAY_URL = '/v1/customer';

    public function getMerchantIntegrations(string $partnerId, ApiContextInterface $context): MerchantIntegrations;

    public function getDisputes(ApiContextInterface $context): Disputes;

    public function getDispute(string $disputeId, ApiContextInterface $context): DisputeItem;

    public function getCredentials(string $partnerId, ApiContextInterface $context): Credentials;
}
