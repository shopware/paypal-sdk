<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Gateway;

use Http\Discovery\Psr18Client;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\SimpleCache\CacheInterface;
use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;
use Shopware\PayPalSDK\Contract\Gateway\TokenGatewayInterface;
use Shopware\PayPalSDK\Contract\RequestServiceInterface;
use Shopware\PayPalSDK\Exception\ApiException;
use Shopware\PayPalSDK\Exception\ExceptionFactory;
use Shopware\PayPalSDK\RequestService;
use Shopware\PayPalSDK\Struct\V1\Token;
use Shopware\PayPalSDK\Util\TokenArrayCache;

class TokenGateway implements TokenGatewayInterface
{
    public const GATEWAY_URL = '/v1/oauth2/token';

    public function __construct(
        protected readonly ClientInterface $client = new Psr18Client(),
        protected readonly CacheInterface $tokenCache = new TokenArrayCache(),
        protected readonly RequestServiceInterface $requestService = new RequestService(),
    ) {}

    /**
     * @throws ApiException|ClientExceptionInterface|\JsonException
     */
    public function getToken(ApiContextInterface $context): Token
    {
        $context = $context->withHeader(RequestServiceInterface::HEADER_CONTENT_TYPE, RequestServiceInterface::CONTENT_TYPE_URL_ENCODED);

        $cacheKey = $context->getOAuthContext()->getCacheKey($context);

        if ($token = $this->getCachedToken($cacheKey)) {
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

        $this->setCachedToken($token, $cacheKey);

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
