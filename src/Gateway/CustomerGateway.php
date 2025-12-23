<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Gateway;

use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;
use Shopware\PayPalSDK\Struct\V1\Disputes;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item as DisputeItem;
use Shopware\PayPalSDK\Struct\V1\MerchantIntegrations;
use Shopware\PayPalSDK\Struct\V1\MerchantIntegrations\Credentials;
use Shopware\PayPalSDK\Struct\V1\MerchantTracking;
use Shopware\PayPalSDK\Struct\V2\Referral;
use Shopware\PayPalSDK\Struct\V3\ManagedAccount;

class CustomerGateway extends AbstractGateway
{
    public const GATEWAY_URL = '/v1/customer';
    public const GATEWAY_URL_V2 = '/v2/customer';
    public const GATEWAY_URL_V3 = '/v3/customer';

    public function getMerchantIntegrations(string $partnerId, string $merchantId, ApiContextInterface $context): MerchantIntegrations
    {
        return $this->request(
            'GET',
            self::GATEWAY_URL . '/partners/' . $partnerId . '/merchant-integrations/' . $merchantId,
            null,
            MerchantIntegrations::class,
            $context->withThirdParty(false),
        );
    }

    public function getMerchantTracking(string $partnerId, string $trackingId, ApiContextInterface $context): MerchantTracking
    {
        return $this->request(
            'GET',
            self::GATEWAY_URL . '/partners/' . $partnerId . '/merchant-integrations',
            null,
            MerchantTracking::class,
            $context->withQueryParameter('tracking_id', $trackingId)->withThirdParty(false),
        );
    }

    public function getCredentials(string $partnerId, ApiContextInterface $context): Credentials
    {
        return $this->request(
            'GET',
            self::GATEWAY_URL . '/partners/' . $partnerId . '/merchant-integrations/credentials',
            null,
            Credentials::class,
            $context->withThirdParty(false),
        );
    }

    public function createPartnerReferral(Referral $referral, ApiContextInterface $context): Referral
    {
        return $this->request(
            'POST',
            self::GATEWAY_URL_V2 . '/partner-referrals',
            $referral,
            Referral::class,
            $context->withThirdParty(false),
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

    public function getManagedAccount(string $merchantId, ApiContextInterface $context): ManagedAccount
    {
        return $this->request(
            'GET',
            self::GATEWAY_URL_V3 . '/managed-accounts/' . $merchantId,
            null,
            ManagedAccount::class,
            $context->withThirdParty(false),
        );
    }
}
