<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Contract\Gateway;

use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;
use Shopware\PayPalSDK\Struct\V1\Token;

interface TokenGatewayInterface extends GatewayInterface
{
    /**
     * Request an {@see Token} from PayPal's OAuth2 endpoint.
     */
    public function getToken(ApiContextInterface $context): Token;
}
