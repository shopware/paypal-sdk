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

interface RequestServiceInterface
{
    public const CONTENT_TYPE_URL_ENCODED = 'application/x-www-form-urlencoded';
    public const CONTENT_TYPE_JSON = 'application/json';

    public const ALG_NONE_HEADER = 'eyJhbGciOiJub25lIn0=';

    public function createRequest(string $method, string $path, ApiContextInterface $context): RequestInterface;

    /**
     * @param array<mixed>|\JsonSerializable $body
     */
    public function withBody(RequestInterface $request, array|\JsonSerializable $body): RequestInterface;

    /**
     * @throws ApiException
     *
     * @return ($forceBody is true ? array<mixed> : array<mixed>|null)
     */
    public function handleResponse(ResponseInterface $response, bool $forceBody): ?array;
}
