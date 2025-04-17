<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Test\Request;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;

class TestRequestContext
{
    protected ?ResponseInterface $response = null;

    /**
     * @param class-string $gatewayClass
     * @param array<mixed>|null $requestBody
     */
    public function __construct(
        protected readonly string $gatewayClass,
        protected readonly string $gatewayMethod,
        protected readonly RequestInterface $request,
        protected readonly ApiContextInterface $context,
        protected readonly ?array $requestBody,
    ) {}

    public function withResponse(ResponseInterface $response): self
    {
        $self = clone $this;
        $self->response = $response;

        return $self;
    }

    /**
     * @param class-string $gatewayClass
     */
    public function isGateway(string $gatewayClass): bool
    {
        return \is_a($this->gatewayClass, $gatewayClass, true);
    }

    public function getGatewayClass(): string
    {
        return $this->gatewayClass;
    }

    public function getGatewayMethod(): string
    {
        return $this->gatewayMethod;
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function getContext(): ApiContextInterface
    {
        return $this->context;
    }

    /**
     * @return array<mixed>|null
     */
    public function getRequestBody(): ?array
    {
        return $this->requestBody;
    }

    public function getResponse(): ResponseInterface
    {
        if ($this->response === null) {
            throw new \RuntimeException(__METHOD__ . ' is only available after a response has been generated');
        }

        return $this->response;
    }
}
