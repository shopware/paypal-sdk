<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Context;

use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;

/**
 * A context used to receive a client token.
 * Used to safely interact on the client side.
 */
class ClientTokenOAuthContext extends CredentialsOAuthContext
{
    public function getCacheKey(ApiContextInterface $context): ?string
    {
        return \hash('xxh128', 'client-token-%s' . parent::getCacheKey($context));
    }

    public function getBody(): array
    {
        return [
            ...parent::getBody(),
            'response_type' => 'client_token',
        ];
    }
}
