<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Gateway;

use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\SimpleCache\CacheInterface;
use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;
use Shopware\PayPalSDK\Contract\Gateway\TokenGatewayInterface;
use Shopware\PayPalSDK\Contract\RequestServiceInterface;
use Shopware\PayPalSDK\Exception\ExceptionFactory;
use Shopware\PayPalSDK\RequestService;
use Shopware\PayPalSDK\Struct\V1\Token;
use Shopware\PayPalSDK\Util\TokenArrayCache;

class TokenGateway implements TokenGatewayInterface
{
    protected readonly ClientInterface $client;

    public function __construct(
        protected readonly CacheInterface $tokenCache = new TokenArrayCache(),
        protected readonly RequestServiceInterface $requestService = new RequestService(),
        ?ClientInterface $client = null,
    ) {
        $this->client = $client ?? Psr18ClientDiscovery::find();
    }

    public function getToken(ApiContextInterface $context): Token
    {
        $context = $context->withHeader('Content-Type', RequestServiceInterface::CONTENT_TYPE_URL_ENCODED);

        if ($token = $this->getCachedToken($context->getOAuthContext()->getCacheKey())) {
            return $token;
        }

        $request = $this->requestService->createRequest('POST', self::GATEWAY_URL, $context);
        $request = $this->requestService->withBody($request, $context->getOAuthContext()->getBody());

        foreach ($context->getOAuthContext()->getHeaders() as $key => $value) {
            $request = $request->withHeader($key, $value);
        }

        $response = $this->client->sendRequest($request);
        $content = $this->requestService->handleResponse($response);

        if (!$content) {
            throw ExceptionFactory::createFromResponse($response);
        }

        $token = (new Token())->assign($content);

        $this->setCachedToken($token, $context->getOAuthContext()->getCacheKey());

        return $token;
    }

    protected function getCachedToken(?string $key): ?Token
    {
        if (!$key) {
            return null;
        }

        $token = $this->tokenCache->get($key);
        $token = $token instanceof Token ? $token : null;

        if (!$token || $token->getExpireDateTime() < new \DateTime('now', new \DateTimeZone('UTC'))) {
            $this->tokenCache->delete($key);
            $token = null;
        }

        return $token;
    }

    protected function setCachedToken(#[\SensitiveParameter] Token $token, ?string $key): void
    {
        if (!$key) {
            return;
        }

        $this->tokenCache->set($key, $token, $token->getExpiresIn() - Token::TTL_THRESHOLD_SEC);
    }
}
