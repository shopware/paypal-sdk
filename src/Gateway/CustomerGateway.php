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
use Shopware\PayPalSDK\Struct\V2\Referral;

class CustomerGateway extends AbstractGateway implements CustomerGatewayInterface
{
    public function getMerchantIntegrations(string $partnerId, ApiContextInterface $context): MerchantIntegrations
    {
        return $this->request(
            'GET',
            self::GATEWAY_URL . '/partners/' . $partnerId . '/merchant-integrations' . ($context->getMerchantId() ? '/' . $context->getMerchantId() : ''),
            null,
            MerchantIntegrations::class,
            $context,
        );
    }

    public function getDisputes(ApiContextInterface $context): Disputes
    {
        return $this->request(
            'GET',
            self::GATEWAY_URL . '/disputes',
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

    public function getCredentials(string $partnerId, ApiContextInterface $context): Credentials
    {
        return $this->request(
            'GET',
            self::GATEWAY_URL . '/partners/' . $partnerId . '/merchant-integrations/credentials',
            null,
            Credentials::class,
            $context,
        );
    }

    public function createPartnerReferral(Referral $referral, ApiContextInterface $context): Referral
    {
        return $this->request(
            'POST',
            self::GATEWAY_URL_V2 . '/partner-referrals',
            $referral,
            Referral::class,
            $context,
        );
    }
}
