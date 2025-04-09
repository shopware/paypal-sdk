<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Contract;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;
use Shopware\PayPalSDK\Exception\ApiException;

/**
 * Service that assists in creating a request to, and handling the response from, the PayPal API.
 */
interface RequestServiceInterface
{
    public const HEADER_CONTENT_TYPE = 'Content-Type';

    public const CONTENT_TYPE_URL_ENCODED = 'application/x-www-form-urlencoded';
    public const CONTENT_TYPE_JSON = 'application/json';
    public const JSON_CONTENT_METHODS = ['PATCH', 'PUT', 'POST'];

    public const ALG_NONE_HEADER = 'eyJhbGciOiJub25lIn0=';

    /**
     * Create a request based on the given $method, $path and $context.
     * All headers and query parameters which are available in $context.
     *
     * If {@see $method} is one of {@see self::JSON_CONTENT_METHODS}, the content type header is set to {@see self::CONTENT_TYPE_JSON}.
     */
    public function createRequest(string $method, string $path, ApiContextInterface $context): RequestInterface;

    /**
     * Set a body into the request.
     * If the content type header is set to {@see self::CONTENT_TYPE_URL_ENCODED}, the body will be form url encoded.
     * Otherwise, the body will be set as {@see self::CONTENT_TYPE_JSON}.
     *
     * @param array<mixed>|\JsonSerializable $body
     */
    public function withBody(RequestInterface $request, array|\JsonSerializable $body): RequestInterface;

    /**
     * Handle any response from PayPal and return the contained content.
     * If the response was not successfully, an {@see ApiException} is thrown.
     *
     * @throws ApiException
     *
     * @return array<mixed>|null
     */
    public function handleResponse(ResponseInterface $response): ?array;
}
