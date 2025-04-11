<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Contract\Gateway;

use Psr\Http\Client\ClientExceptionInterface;
use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;
use Shopware\PayPalSDK\Exception\ApiException;
use Shopware\PayPalSDK\Struct\V1\Token;

interface TokenGatewayInterface extends GatewayInterface
{
    /**
     * Request an {@see Token} from PayPal's OAuth2 endpoint.
     *
     * @throws ApiException|ClientExceptionInterface|\JsonException
     */
    public function getToken(ApiContextInterface $context): Token;
}
