<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Gateway;

use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;
use Shopware\PayPalSDK\Contract\Gateway\CustomerGatewayInterface;
use Shopware\PayPalSDK\Struct\V1\Disputes;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item as DisputeItem;
use Shopware\PayPalSDK\Struct\V1\MerchantIntegrations;
use Shopware\PayPalSDK\Struct\V1\MerchantIntegrations\Credentials;

class CustomerGateway extends AbstractGateway implements CustomerGatewayInterface
{
    public function getMerchantIntegrations(ApiContextInterface $context): MerchantIntegrations
    {
        return $this->request(
            'GET',
            self::GATEWAY_URL . '/partners/' . $context->getPartnerId() . '/merchant-integrations/' . $context->getMerchantId(),
            null,
            MerchantIntegrations::class,
            $context,
        );
    }

    public function getDisputes(?string $disputeStateFilter, ApiContextInterface $context): Disputes
    {
        $queryParameter = [];
        if ($disputeStateFilter !== null) {
            $queryParameter['dispute_state'] = $disputeStateFilter;
        }

        return $this->request(
            'GET',
            self::GATEWAY_URL . '/disputes?' . \http_build_query($queryParameter),
            null,
            Disputes::class,
            $context,
        );
    }

    public function getDispute(string $disputeId, ApiContextInterface $context): DisputeItem
    {
        return $this->request(
            'GET',
            self::GATEWAY_URL . '/disputes/' . $disputeId,
            null,
            DisputeItem::class,
            $context,
        );
    }

    public function getCredentials(ApiContextInterface $context): Credentials
    {
        return $this->request(
            'GET',
            self::GATEWAY_URL . 'customer/partners/' . $context->getPartnerId() . '/merchant-integrations/credentials',
            null,
            Credentials::class,
            $context,
        );
    }
}
