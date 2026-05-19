<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Reporting;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Common\Link;
use Shopware\PayPalSDK\Struct\V1\Common\LinkCollection;
use Shopware\PayPalSDK\Struct\V1\Reporting\Transactions\TransactionDetail;
use Shopware\PayPalSDK\Struct\V1\Reporting\Transactions\TransactionDetailCollection;

#[OA\Schema(schema: 'paypal_v1_reporting_transactions')]
class Transactions extends Struct
{
    #[OA\Property(type: 'array', items: new OA\Items(ref: TransactionDetail::class), nullable: true)]
    protected ?TransactionDetailCollection $transactionDetails = null;

    #[OA\Property(type: 'string')]
    protected string $accountNumber;

    #[OA\Property(type: 'string')]
    protected string $startDate;

    #[OA\Property(type: 'string')]
    protected string $endDate;

    #[OA\Property(type: 'string')]
    protected string $lastRefreshedDatetime;

    #[OA\Property(type: 'integer')]
    protected int $page;

    #[OA\Property(type: 'integer')]
    protected int $totalItems;

    #[OA\Property(type: 'integer')]
    protected int $totalPages;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Link::class))]
    protected LinkCollection $links;

    public function getTransactionDetails(): ?TransactionDetailCollection
    {
        return $this->transactionDetails;
    }

    public function setTransactionDetails(?TransactionDetailCollection $transactionDetails): void
    {
        $this->transactionDetails = $transactionDetails;
    }

    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }

    public function setAccountNumber(string $accountNumber): void
    {
        $this->accountNumber = $accountNumber;
    }

    public function getStartDate(): string
    {
        return $this->startDate;
    }

    public function setStartDate(string $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): string
    {
        return $this->endDate;
    }

    public function setEndDate(string $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getLastRefreshedDatetime(): string
    {
        return $this->lastRefreshedDatetime;
    }

    public function setLastRefreshedDatetime(string $lastRefreshedDatetime): void
    {
        $this->lastRefreshedDatetime = $lastRefreshedDatetime;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    public function setTotalItems(int $totalItems): void
    {
        $this->totalItems = $totalItems;
    }

    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    public function setTotalPages(int $totalPages): void
    {
        $this->totalPages = $totalPages;
    }

    public function getLinks(): LinkCollection
    {
        return $this->links;
    }

    public function setLinks(LinkCollection $links): void
    {
        $this->links = $links;
    }
}
