<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\WalletDomain;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v1_wallet_domain_merchant')]
class Merchant extends Struct
{
    #[OA\Property(type: 'string')]
    protected string $accountId;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $businessName = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $url = null;

    public function getAccountId(): string
    {
        return $this->accountId;
    }

    public function setAccountId(string $accountId): void
    {
        $this->accountId = $accountId;
    }

    public function getBusinessName(): ?string
    {
        return $this->businessName;
    }

    public function setBusinessName(?string $businessName): void
    {
        $this->businessName = $businessName;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }
}
