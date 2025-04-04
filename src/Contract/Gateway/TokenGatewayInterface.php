<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Contract\Gateway;

use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;
use Shopware\PayPalSDK\Struct\V1\Token;

interface TokenGatewayInterface
{
    public const GATEWAY_URL = '/v1/oauth2/token';

    public function getToken(ApiContextInterface $context): Token;
}
