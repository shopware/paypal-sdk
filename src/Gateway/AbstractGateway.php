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
use Shopware\PayPalSDK\Exception\ExceptionFactory;
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
     * @throws ApiException|ClientExceptionInterface|\JsonException
     *
     * @return ($responseClass is null ? null : T)
     */
    protected function request(string $method, string $path, Struct|Collection|null $body, ?string $responseClass, ApiContextInterface $context): ?Struct
    {
        $token = $this->tokenGateway->getToken($context);

        $request = $this->requestService->createRequest($method, $path, $context)
            ->withHeader('Authorization', \sprintf('%s %s', $token->getTokenType(), $token->getAccessToken()));

        if ($body) {
            $request = $this->requestService->withBody($request, $body);
        }

        $response = $this->client->sendRequest($request);
        $content = $this->requestService->handleResponse($response);

        if ($responseClass) {
            if ($content === null) {
                throw ExceptionFactory::createFromResponse($response);
            }

            return Struct::from($responseClass, $content);
        }

        return null;
    }
}
