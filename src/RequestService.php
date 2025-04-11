<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK;

use Http\Discovery\Psr17Factory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;
use Shopware\PayPalSDK\Contract\Context\CredentialsOAuthContextInterface;
use Shopware\PayPalSDK\Contract\RequestServiceInterface;
use Shopware\PayPalSDK\Exception\ApiException;
use Shopware\PayPalSDK\Exception\ExceptionFactory;

class RequestService implements RequestServiceInterface
{
    public function __construct(
        protected readonly Psr17Factory $factory = new Psr17Factory(),
    ) {}

    public function createRequest(string $method, string $path, ApiContextInterface $context): RequestInterface
    {
        $uri = $this->factory
            ->createUri($context->isSandbox() ? Constants::BASEURL_SANDBOX : Constants::BASEURL_LIVE)
            ->withPath($path)
            ->withQuery(\http_build_query($context->getQueryParameters()));

        $request = $this->factory->createRequest($method, $uri);

        if ($assertion = $this->getAuthAssertion($context)) {
            $request = $request->withHeader('PayPal-Auth-Assertion', $assertion);
        }

        if (\in_array(\strtoupper($method), self::JSON_CONTENT_METHODS, true)) {
            $request = $request->withHeader(self::HEADER_CONTENT_TYPE, self::CONTENT_TYPE_JSON);
        }

        foreach ($context->getHeaders() as $key => $value) {
            $request = $request->withHeader($key, $value);
        }

        return $request;
    }

    /**
     * @throws \JsonException
     * @throws \InvalidArgumentException
     */
    public function withBody(RequestInterface $request, array|\JsonSerializable $body): RequestInterface
    {
        if (!($contentType = $request->getHeaderLine(self::HEADER_CONTENT_TYPE))) {
            return $request;
        }

        $body = match (true) {
            \str_contains($contentType, self::CONTENT_TYPE_URL_ENCODED) => \http_build_query($body),
            \str_contains($contentType, self::CONTENT_TYPE_JSON) => \json_encode($body, \JSON_THROW_ON_ERROR),
            default => throw new \InvalidArgumentException(__METHOD__ . ' is unable to handle content-type "' . $contentType . '"'),
        };

        return $request->withBody($this->factory->createStream($body));
    }

    /**
     * @throws ApiException
     */
    public function handleResponse(ResponseInterface $response): ?array
    {
        $body = (string) $response->getBody();

        if ($response->getStatusCode() >= 400 || $body) {
            try {
                $content = \json_decode($body, true, flags: \JSON_THROW_ON_ERROR);
            } catch (\JsonException) {
                throw ExceptionFactory::createFromResponse($response);
            }

            if ($response->getStatusCode() >= 400 || !\is_array($content)) {
                throw ExceptionFactory::createFromResponse($response);
            }

            return $content;
        }

        return null;
    }

    protected function getAuthAssertion(ApiContextInterface $context): ?string
    {
        if (!$context->isThirdParty()) {
            return null;
        }

        $oauthContext = $context->getOAuthContext();

        if (!$oauthContext instanceof CredentialsOAuthContextInterface) {
            return null;
        }

        $payload = [
            'iss' => $oauthContext->getClientId(),
            'payer_id' => $context->getMerchantId(),
        ];

        return \sprintf('%s.%s.', self::ALG_NONE_HEADER, \base64_encode(\json_encode($payload, \JSON_THROW_ON_ERROR)));
    }
}
