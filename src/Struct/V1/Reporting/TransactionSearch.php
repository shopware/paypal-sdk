<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Reporting;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v1_reporting_transaction_search')]
class TransactionSearch extends Struct
{
    #[OA\Property(type: 'string', nullable: true, minLength: 17, maxLength: 19)]
    protected ?string $transactionId = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $transactionType = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $transactionStatus = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $transactionAmount = null;

    #[OA\Property(type: 'string', nullable: true, maxLength: 3, minLength: 3)]
    protected ?string $transactionCurrency = null;

    #[OA\Property(type: 'string', format: 'date-time')]
    protected \DateTimeInterface $startDate;

    #[OA\Property(type: 'string', format: 'date-time')]
    protected \DateTimeInterface $endDate;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $paymentInstrumentType = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $storeId = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $terminalId = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $fields = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $balanceAffectingRecordsOnly = null;

    #[OA\Property(type: 'integer', nullable: true, minimum: 1, maximum: 500)]
    protected ?int $pageSize = null;

    #[OA\Property(type: 'integer', nullable: true, minimum: 1, maximum: 2147483647)]
    protected ?int $page = null;

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function setTransactionId(?string $transactionId): void
    {
        $this->transactionId = $transactionId;
    }

    public function getTransactionType(): ?string
    {
        return $this->transactionType;
    }

    public function setTransactionType(?string $transactionType): void
    {
        $this->transactionType = $transactionType;
    }

    public function getTransactionStatus(): ?string
    {
        return $this->transactionStatus;
    }

    public function setTransactionStatus(?string $transactionStatus): void
    {
        $this->transactionStatus = $transactionStatus;
    }

    public function getTransactionAmount(): ?string
    {
        return $this->transactionAmount;
    }

    public function setTransactionAmount(?string $transactionAmount): void
    {
        $this->transactionAmount = $transactionAmount;
    }

    public function getTransactionCurrency(): ?string
    {
        return $this->transactionCurrency;
    }

    public function setTransactionCurrency(?string $transactionCurrency): void
    {
        $this->transactionCurrency = $transactionCurrency;
    }

    public function getStartDate(): \DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): \DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getPaymentInstrumentType(): ?string
    {
        return $this->paymentInstrumentType;
    }

    public function setPaymentInstrumentType(?string $paymentInstrumentType): void
    {
        $this->paymentInstrumentType = $paymentInstrumentType;
    }

    public function getStoreId(): ?string
    {
        return $this->storeId;
    }

    public function setStoreId(?string $storeId): void
    {
        $this->storeId = $storeId;
    }

    public function getTerminalId(): ?string
    {
        return $this->terminalId;
    }

    public function setTerminalId(?string $terminalId): void
    {
        $this->terminalId = $terminalId;
    }

    public function getFields(): ?string
    {
        return $this->fields;
    }

    public function setFields(?string $fields): void
    {
        $this->fields = $fields;
    }

    public function getBalanceAffectingRecordsOnly(): ?string
    {
        return $this->balanceAffectingRecordsOnly;
    }

    public function setBalanceAffectingRecordsOnly(?string $balanceAffectingRecordsOnly): void
    {
        $this->balanceAffectingRecordsOnly = $balanceAffectingRecordsOnly;
    }

    public function getPageSize(): ?int
    {
        return $this->pageSize;
    }

    public function setPageSize(?int $pageSize): void
    {
        $this->pageSize = $pageSize;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function setPage(?int $page): void
    {
        $this->page = $page;
    }
}
