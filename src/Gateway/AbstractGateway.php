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
use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;
use Shopware\PayPalSDK\Contract\Gateway\GatewayInterface;
use Shopware\PayPalSDK\Contract\Gateway\TokenGatewayInterface;
use Shopware\PayPalSDK\Contract\RequestServiceInterface;
use Shopware\PayPalSDK\Exception\ApiException;
use Shopware\PayPalSDK\RequestService;
use Shopware\PayPalSDK\Struct\Collection;
use Shopware\PayPalSDK\Struct\Struct;

abstract class AbstractGateway implements GatewayInterface
{
    public function __construct(
        protected readonly ClientInterface $client = new Psr18Client(),
        protected readonly TokenGatewayInterface $tokenGateway = new TokenGateway(),
        protected readonly RequestServiceInterface $requestService = new RequestService(),
    ) {}

    /**
     * @template T of Struct
     *
     * @param class-string<T>|null $responseClass
     *
     * @throws ApiException|ClientExceptionInterface|\JsonException|\LogicException
     *
     * @return ($responseClass is null ? null : T)
     */
    protected function request(string $method, string $path, Struct|Collection|null $body, ?string $responseClass, ApiContextInterface $context): ?Struct
    {
        $content = $this->_request($method, $path, $body, $context);

        if ($responseClass) {
            if ($content === null) {
                throw new \LogicException('Expected response content for deserializing into ' . $responseClass);
            }

            return Struct::from($responseClass, $content);
        }

        return null;
    }

    /**
     * @return array<mixed>|null
     */
    private function _request(string $method, string $path, Struct|Collection|null $body, ApiContextInterface $context, ?ApiException $rejection = null): ?array
    {
        /** @phpstan-ignore-next-line arguments.count - $refresh will be a real parameter with v3.0.0 */
        $token = $this->tokenGateway->getToken($context, $rejection !== null);

        if ($rejection && $token->isCached()) {
            // requested cache refresh, but got a cached token again, implementation not up-to-date
            throw $rejection;
        }

        $request = $this->requestService->createRequest($method, $path, $context)
            ->withHeader('Authorization', \sprintf('%s %s', $token->getTokenType(), $token->getAccessToken()));

        if ($body) {
            $request = $this->requestService->withBody($request, $body);
        }

        try {
            $response = $this->client->sendRequest($request);

            return $this->requestService->handleResponse($response);
        } catch (ApiException $e) {
            if (!$rejection && $token->isCached() && $e->is('invalid_token', ApiException::CODE_INVALID_TOKEN)) {
                return $this->_request($method, $path, $body, $context, $e);
            }

            throw $e;
        }
    }
}
