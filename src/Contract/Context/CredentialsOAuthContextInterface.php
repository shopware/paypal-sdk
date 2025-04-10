<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Contract\Context;

use Shopware\PayPalSDK\RequestService;

/**
 * A context used for authenticating against the token endpoint including a client ID.
 */
interface CredentialsOAuthContextInterface extends OAuthContextInterface
{
    /**
     * Client ID to be used for authentication.
     * In a third-party context, this is used for an auth assertion.
     * See {@see RequestService::getAuthAssertion}
     */
    public function getClientId(): string;
}
