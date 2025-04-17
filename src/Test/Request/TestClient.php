<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Test\Request;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;
use Shopware\PayPalSDK\Contract\Gateway\GatewayInterface;
use Shopware\PayPalSDK\Exception\ApiException;
use Shopware\PayPalSDK\Gateway\AbstractGateway;

/**
 * @phpstan-type Handler \Closure(TestRequestContext): ?ResponseInterface
 * @phpstan-type Filter (\Closure(TestRequestContext): bool)|(\Closure(TestRequestContext, int): bool)
 */
class TestClient implements ClientInterface
{
    /** @var list<TestRequestContext> */
    protected array $requests = [];

    /** @var Handler */
    protected \Closure $handler;

    /**
     * @param list<ResponseInterface> $responses
     * @param Handler $handler
     */
    public function __construct(
        protected array $responses = [],
        ?\Closure $handler = null,
    ) {
        $this->handler = $handler ?? fn () => \array_shift($this->responses);
    }

    /**
     * @throws ApiException|\JsonException|\RuntimeException
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $info = $this->extractCallerInformation();

        if (\array_filter($info) !== $info) {
            throw new \RuntimeException('Requests send though ' . __CLASS__ . ' has to come from ' . GatewayInterface::class . ' and should be called with an ' . ApiContextInterface::class);
        }

        $requestContext = new TestRequestContext(
            $info['class'],
            $info['function'],
            $request,
            $info['context'],
            $this->extractBody($request),
        );

        $response = ($this->handler)($requestContext);

        if (!$response) {
            throw new \RuntimeException(\sprintf(
                'No response given to handle request "%s %s"',
                $request->getMethod(),
                (string) $request->getUri(),
            ));
        }

        $this->requests[] = $requestContext->withResponse($response);

        return $response;
    }

    /**
     * @return array{class: class-string<GatewayInterface>|null, function: string|null, context: ApiContextInterface|null}
     */
    protected function extractCallerInformation(int $limit = 10): array
    {
        $info = ['class' => null, 'function' => null, 'context' => null];

        /**
         * 0: self::extractCallerInformation
         * 1: self::sendRequest
         * 2: AbstractGateway::request / TokenGateway::getToken
         * + extra offset for abstraction or extensions
         */
        foreach (\debug_backtrace(0, limit: 3 + $limit) as $trace) {
            if (!isset($trace['class']) || !\is_a($trace['class'], GatewayInterface::class, true)) {
                continue;
            }

            if ($trace['class'] !== AbstractGateway::class) {
                $info['class'] ??= $trace['class'];
                $info['function'] ??= $trace['function'];
            }

            if (!isset($trace['args'])) {
                continue;
            }

            foreach ($trace['args'] as $arg) {
                if ($arg instanceof ApiContextInterface) {
                    $info['context'] ??= $arg;
                }
            }
        }

        return $info;
    }

    /**
     * @return array<mixed>|null
     */
    protected function extractBody(RequestInterface $request): ?array
    {
        $body = (string) $request->getBody();

        if (!$body) {
            return null;
        }

        $content = \json_decode($body, true);

        if ($content === false) {
            \parse_str($body, $content);
        } elseif (!\is_array($content)) {
            $content = [$content];
        }

        return $content;
    }

    public function addResponse(ResponseInterface $response): void
    {
        $this->responses[] = $response;
    }

    /**
     * Resets all logged requests.
     */
    public function resetRequests(): void
    {
        $this->requests = [];
    }

    /**
     * @return list<TestRequestContext>
     */
    public function getAll(): array
    {
        return $this->requests;
    }

    public function get(int $idx): ?TestRequestContext
    {
        return $this->requests[$idx] ?? null;
    }

    public function getLast(): ?TestRequestContext
    {
        return \end($this->requests) ?: null;
    }

    public function getFirst(): ?TestRequestContext
    {
        return $this->requests[0] ?? null;
    }

    /**
     * @param Filter $closure
     */
    public function getFirstWhere(\Closure $closure): ?TestRequestContext
    {
        return \current(\array_filter($this->requests, $closure, \ARRAY_FILTER_USE_BOTH)) ?: null;
    }

    /**
     * @param Filter $closure
     */
    public function getLastWhere(\Closure $closure): ?TestRequestContext
    {
        return \current(\array_filter(\array_reverse($this->requests, true), $closure, \ARRAY_FILTER_USE_BOTH)) ?: null;
    }
}
