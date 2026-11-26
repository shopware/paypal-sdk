<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Reporting\Transactions;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Pattern;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v1_reporting_transactions_auction_info')]
class AuctionInfo extends Struct
{
    #[OA\Property(type: 'string', minLength: 1, maxLength: 200, pattern: Pattern::TEXT, nullable: true)]
    protected ?string $auctionSite = null;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 4000, pattern: Pattern::TEXT, nullable: true)]
    protected ?string $auctionItemSite = null;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 500, pattern: Pattern::TEXT, nullable: true)]
    protected ?string $auctionBuyerId = null;

    #[OA\Property(type: 'string', format: 'date-time', minLength: 20, maxLength: 64, pattern: Pattern::DATE_TIME, nullable: true)]
    protected ?string $auctionClosingDate = null;

    public function getAuctionSite(): ?string
    {
        return $this->auctionSite;
    }

    public function setAuctionSite(?string $auctionSite): void
    {
        $this->auctionSite = $auctionSite;
    }

    public function getAuctionItemSite(): ?string
    {
        return $this->auctionItemSite;
    }

    public function setAuctionItemSite(?string $auctionItemSite): void
    {
        $this->auctionItemSite = $auctionItemSite;
    }

    public function getAuctionBuyerId(): ?string
    {
        return $this->auctionBuyerId;
    }

    public function setAuctionBuyerId(?string $auctionBuyerId): void
    {
        $this->auctionBuyerId = $auctionBuyerId;
    }

    public function getAuctionClosingDate(): ?string
    {
        return $this->auctionClosingDate;
    }

    public function setAuctionClosingDate(?string $auctionClosingDate): void
    {
        $this->auctionClosingDate = $auctionClosingDate;
    }
}
