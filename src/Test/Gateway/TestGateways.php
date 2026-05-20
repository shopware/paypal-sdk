<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Test\Gateway;

use Psr\Http\Client\ClientInterface;
use Psr\SimpleCache\CacheInterface;
use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;
use Shopware\PayPalSDK\Gateway\CustomerGateway;
use Shopware\PayPalSDK\Gateway\OrderGateway;
use Shopware\PayPalSDK\Gateway\PaymentGateway;
use Shopware\PayPalSDK\Gateway\PaymentV1Gateway;
use Shopware\PayPalSDK\Gateway\ReportingGateway;
use Shopware\PayPalSDK\Gateway\TokenGateway;
use Shopware\PayPalSDK\Gateway\WebhookGateway;
use Shopware\PayPalSDK\RequestService;
use Shopware\PayPalSDK\Struct\V1\Token;
use Shopware\PayPalSDK\Util\TokenArrayCache;

class TestGateways
{
    public function __construct(
        protected ClientInterface $client,
        protected RequestService $requestService = new RequestService(),
        protected CacheInterface $tokenCache = new TokenArrayCache(),
    ) {}

    public function getTokenCache(): CacheInterface
    {
        return $this->tokenCache;
    }

    public function setCachedToken(ApiContextInterface $context, ?Token $token = null): void
    {
        $token ??= (new Token())->assign(['access_token' => 'ACCESS-TOKEN', 'token_type' => 'Bearer', 'expires_in' => 36000]);

        if ($cacheKey = $context->getOAuthContext()->getCacheKey($context)) {
            $this->tokenCache->set($cacheKey, $token);
        }
    }

    public function customerGateway(): CustomerGateway
    {
        return new CustomerGateway($this->client, $this->tokenGateway(), $this->requestService);
    }

    public function orderGateway(): OrderGateway
    {
        return new OrderGateway($this->client, $this->tokenGateway(), $this->requestService);
    }

    public function paymentGateway(): PaymentGateway
    {
        return new PaymentGateway($this->client, $this->tokenGateway(), $this->requestService);
    }

    public function paymentV1Gateway(): PaymentV1Gateway
    {
        return new PaymentV1Gateway($this->client, $this->tokenGateway(), $this->requestService);
    }

    public function reportingGateway(): ReportingGateway
    {
        return new ReportingGateway($this->client, $this->tokenGateway(), $this->requestService);
    }

    public function tokenGateway(): TokenGateway
    {
        return new TokenGateway($this->client, $this->tokenCache, $this->requestService);
    }

    public function webhookGateway(): WebhookGateway
    {
        return new WebhookGateway($this->client, $this->tokenGateway(), $this->requestService);
    }
}
