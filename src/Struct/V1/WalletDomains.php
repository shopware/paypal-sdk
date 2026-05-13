<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Common\Link;
use Shopware\PayPalSDK\Struct\V1\Common\LinkCollection;
use Shopware\PayPalSDK\Struct\V1\WalletDomain\WalletDomainCollection;

#[OA\Schema(schema: 'paypal_v1_wallet_domains')]
class WalletDomains extends Struct
{
    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $totalItems = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $totalPages = null;

    #[OA\Property(type: 'array', items: new OA\Items(ref: WalletDomain::class))]
    protected WalletDomainCollection $walletDomains;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Link::class), nullable: true)]
    protected ?LinkCollection $links = null;

    public function getTotalItems(): ?string
    {
        return $this->totalItems;
    }

    public function setTotalItems(?string $totalItems): void
    {
        $this->totalItems = $totalItems;
    }

    public function getTotalPages(): ?string
    {
        return $this->totalPages;
    }

    public function setTotalPages(?string $totalPages): void
    {
        $this->totalPages = $totalPages;
    }

    public function getWalletDomains(): WalletDomainCollection
    {
        return $this->walletDomains;
    }

    public function setWalletDomains(WalletDomainCollection $walletDomains): void
    {
        $this->walletDomains = $walletDomains;
    }

    public function getLinks(): ?LinkCollection
    {
        return $this->links;
    }

    public function setLinks(?LinkCollection $links): void
    {
        $this->links = $links;
    }
}
