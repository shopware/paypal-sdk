<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\WalletDomain\Domain;

#[OA\Schema(schema: 'paypal_v1_wallet_domain')]
class WalletDomain extends Struct
{
    #[OA\Property(type: 'string')]
    protected string $providerType;

    #[OA\Property(ref: Domain::class)]
    protected Domain $domain;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $reason = null;

    public function getProviderType(): string
    {
        return $this->providerType;
    }

    public function setProviderType(string $providerType): void
    {
        $this->providerType = $providerType;
    }

    public function getDomain(): Domain
    {
        return $this->domain;
    }

    public function setDomain(Domain $domain): void
    {
        $this->domain = $domain;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): void
    {
        $this->reason = $reason;
    }
}
