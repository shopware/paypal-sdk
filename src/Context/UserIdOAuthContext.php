<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Context;

use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;

/**
 * A context typically used to retrieve a token for a target user based on credentials.
 * Useful for third-party contexts.
 */
class UserIdOAuthContext extends CredentialsOAuthContext
{
    public function __construct(
        #[\SensitiveParameter]
        string $clientId,
        #[\SensitiveParameter]
        string $clientSecret,
        #[\SensitiveParameter]
        protected readonly ?string $targetCustomerId = null,
    ) {
        parent::__construct($clientId, $clientSecret);
    }

    public function getTargetCustomerId(): ?string
    {
        return $this->targetCustomerId;
    }

    public function getCacheKey(ApiContextInterface $context): ?string
    {
        return \hash('xxh128', \sprintf(
            'user-id-%s-%s',
            (string) parent::getCacheKey($context),
            (string) $this->targetCustomerId,
        ));
    }

    public function getBody(): array
    {
        return \array_filter([
            ...parent::getBody(),
            'response_type' => 'id_token',
            'target_customer_id' => $this->getTargetCustomerId(),
        ]);
    }
}
