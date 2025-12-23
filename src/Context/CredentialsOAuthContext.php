<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Context;

use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;
use Shopware\PayPalSDK\Contract\Context\CredentialsOAuthContextInterface;

/**
 * A context typically used to retrieve a token based on given credentials.
 */
class CredentialsOAuthContext implements CredentialsOAuthContextInterface
{
    public function __construct(
        #[\SensitiveParameter]
        private readonly string $clientId,
        #[\SensitiveParameter]
        private readonly string $clientSecret,
    ) {}

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getCacheKey(ApiContextInterface $context): ?string
    {
        return \hash('xxh128', \sprintf(
            'credentials-%s-%s-%s',
            $this->clientId,
            $this->clientSecret,
            $context->isSandbox() ? 'true' : 'false',
        ));
    }

    /**
     * @return array{grant_type: string}
     */
    public function getBody(): array
    {
        return ['grant_type' => 'client_credentials'];
    }

    /**
     * @return array{Authorization: string}
     */
    public function getHeaders(): array
    {
        return ['Authorization' => \sprintf('Basic %s', base64_encode(\sprintf(
            '%s:%s',
            $this->clientId,
            $this->clientSecret,
        )))];
    }

    public function intoUserIdContext(?string $targetCustomerId = null): UserIdOAuthContext
    {
        return new UserIdOAuthContext($this->clientId, $this->clientSecret, $targetCustomerId);
    }

    public function intoClientTokenContext(): ClientTokenOAuthContext
    {
        return new ClientTokenOAuthContext($this->clientId, $this->clientSecret);
    }

    public function __debugInfo(): array
    {
        return [];
    }
}
