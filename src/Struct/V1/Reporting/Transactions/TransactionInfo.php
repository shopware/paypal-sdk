<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Reporting\Transactions;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Reporting\Money;

#[OA\Schema(schema: 'paypal_v1_reporting_transactions_transaction_info')]
class TransactionInfo extends Struct
{
    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $paypalAccountId = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $transactionId = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $paypalReferenceId = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $paypalReferenceIdType = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $transactionEventCode = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $transactionInitiationDate = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $transactionUpdatedDate = null;

    #[OA\Property(ref: Money::class, nullable: true)]
    protected ?Money $transactionAmount = null;

    #[OA\Property(ref: Money::class, nullable: true)]
    protected ?Money $feeAmount = null;

    #[OA\Property(ref: Money::class, nullable: true)]
    protected ?Money $discountAmount = null;

    #[OA\Property(ref: Money::class, nullable: true)]
    protected ?Money $insuranceAmount = null;

    #[OA\Property(ref: Money::class, nullable: true)]
    protected ?Money $salesTaxAmount = null;

    #[OA\Property(ref: Money::class, nullable: true)]
    protected ?Money $shippingAmount = null;

    #[OA\Property(ref: Money::class, nullable: true)]
    protected ?Money $shippingDiscountAmount = null;

    #[OA\Property(ref: Money::class, nullable: true)]
    protected ?Money $shippingTaxAmount = null;

    #[OA\Property(ref: Money::class, nullable: true)]
    protected ?Money $otherAmount = null;

    #[OA\Property(ref: Money::class, nullable: true)]
    protected ?Money $tipAmount = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $transactionStatus = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $transactionSubject = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $transactionNote = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $paymentTrackingId = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $bankReferenceId = null;

    #[OA\Property(ref: Money::class, nullable: true)]
    protected ?Money $endingBalance = null;

    #[OA\Property(ref: Money::class, nullable: true)]
    protected ?Money $availableBalance = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $invoiceId = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $customField = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $protectionEligibility = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $creditTerm = null;

    #[OA\Property(ref: Money::class, nullable: true)]
    protected ?Money $creditTransactionalFee = null;

    #[OA\Property(ref: Money::class, nullable: true)]
    protected ?Money $creditPromotionalFee = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $annualPercentageRate = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $paymentMethodType = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $instrumentType = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $instrumentSubType = null;

    public function getPaypalAccountId(): ?string
    {
        return $this->paypalAccountId;
    }

    public function setPaypalAccountId(?string $paypalAccountId): void
    {
        $this->paypalAccountId = $paypalAccountId;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function setTransactionId(?string $transactionId): void
    {
        $this->transactionId = $transactionId;
    }

    public function getPaypalReferenceId(): ?string
    {
        return $this->paypalReferenceId;
    }

    public function setPaypalReferenceId(?string $paypalReferenceId): void
    {
        $this->paypalReferenceId = $paypalReferenceId;
    }

    public function getPaypalReferenceIdType(): ?string
    {
        return $this->paypalReferenceIdType;
    }

    public function setPaypalReferenceIdType(?string $paypalReferenceIdType): void
    {
        $this->paypalReferenceIdType = $paypalReferenceIdType;
    }

    public function getTransactionEventCode(): ?string
    {
        return $this->transactionEventCode;
    }

    public function setTransactionEventCode(?string $transactionEventCode): void
    {
        $this->transactionEventCode = $transactionEventCode;
    }

    public function getTransactionInitiationDate(): ?string
    {
        return $this->transactionInitiationDate;
    }

    public function setTransactionInitiationDate(?string $transactionInitiationDate): void
    {
        $this->transactionInitiationDate = $transactionInitiationDate;
    }

    public function getTransactionUpdatedDate(): ?string
    {
        return $this->transactionUpdatedDate;
    }

    public function setTransactionUpdatedDate(?string $transactionUpdatedDate): void
    {
        $this->transactionUpdatedDate = $transactionUpdatedDate;
    }

    public function getTransactionAmount(): ?Money
    {
        return $this->transactionAmount;
    }

    public function setTransactionAmount(?Money $transactionAmount): void
    {
        $this->transactionAmount = $transactionAmount;
    }

    public function getFeeAmount(): ?Money
    {
        return $this->feeAmount;
    }

    public function setFeeAmount(?Money $feeAmount): void
    {
        $this->feeAmount = $feeAmount;
    }

    public function getDiscountAmount(): ?Money
    {
        return $this->discountAmount;
    }

    public function setDiscountAmount(?Money $discountAmount): void
    {
        $this->discountAmount = $discountAmount;
    }

    public function getInsuranceAmount(): ?Money
    {
        return $this->insuranceAmount;
    }

    public function setInsuranceAmount(?Money $insuranceAmount): void
    {
        $this->insuranceAmount = $insuranceAmount;
    }

    public function getSalesTaxAmount(): ?Money
    {
        return $this->salesTaxAmount;
    }

    public function setSalesTaxAmount(?Money $salesTaxAmount): void
    {
        $this->salesTaxAmount = $salesTaxAmount;
    }

    public function getShippingAmount(): ?Money
    {
        return $this->shippingAmount;
    }

    public function setShippingAmount(?Money $shippingAmount): void
    {
        $this->shippingAmount = $shippingAmount;
    }

    public function getShippingDiscountAmount(): ?Money
    {
        return $this->shippingDiscountAmount;
    }

    public function setShippingDiscountAmount(?Money $shippingDiscountAmount): void
    {
        $this->shippingDiscountAmount = $shippingDiscountAmount;
    }

    public function getShippingTaxAmount(): ?Money
    {
        return $this->shippingTaxAmount;
    }

    public function setShippingTaxAmount(?Money $shippingTaxAmount): void
    {
        $this->shippingTaxAmount = $shippingTaxAmount;
    }

    public function getOtherAmount(): ?Money
    {
        return $this->otherAmount;
    }

    public function setOtherAmount(?Money $otherAmount): void
    {
        $this->otherAmount = $otherAmount;
    }

    public function getTipAmount(): ?Money
    {
        return $this->tipAmount;
    }

    public function setTipAmount(?Money $tipAmount): void
    {
        $this->tipAmount = $tipAmount;
    }

    public function getTransactionStatus(): ?string
    {
        return $this->transactionStatus;
    }

    public function setTransactionStatus(?string $transactionStatus): void
    {
        $this->transactionStatus = $transactionStatus;
    }

    public function getTransactionSubject(): ?string
    {
        return $this->transactionSubject;
    }

    public function setTransactionSubject(?string $transactionSubject): void
    {
        $this->transactionSubject = $transactionSubject;
    }

    public function getTransactionNote(): ?string
    {
        return $this->transactionNote;
    }

    public function setTransactionNote(?string $transactionNote): void
    {
        $this->transactionNote = $transactionNote;
    }

    public function getPaymentTrackingId(): ?string
    {
        return $this->paymentTrackingId;
    }

    public function setPaymentTrackingId(?string $paymentTrackingId): void
    {
        $this->paymentTrackingId = $paymentTrackingId;
    }

    public function getBankReferenceId(): ?string
    {
        return $this->bankReferenceId;
    }

    public function setBankReferenceId(?string $bankReferenceId): void
    {
        $this->bankReferenceId = $bankReferenceId;
    }

    public function getEndingBalance(): ?Money
    {
        return $this->endingBalance;
    }

    public function setEndingBalance(?Money $endingBalance): void
    {
        $this->endingBalance = $endingBalance;
    }

    public function getAvailableBalance(): ?Money
    {
        return $this->availableBalance;
    }

    public function setAvailableBalance(?Money $availableBalance): void
    {
        $this->availableBalance = $availableBalance;
    }

    public function getInvoiceId(): ?string
    {
        return $this->invoiceId;
    }

    public function setInvoiceId(?string $invoiceId): void
    {
        $this->invoiceId = $invoiceId;
    }

    public function getCustomField(): ?string
    {
        return $this->customField;
    }

    public function setCustomField(?string $customField): void
    {
        $this->customField = $customField;
    }

    public function getProtectionEligibility(): ?string
    {
        return $this->protectionEligibility;
    }

    public function setProtectionEligibility(?string $protectionEligibility): void
    {
        $this->protectionEligibility = $protectionEligibility;
    }

    public function getCreditTerm(): ?string
    {
        return $this->creditTerm;
    }

    public function setCreditTerm(?string $creditTerm): void
    {
        $this->creditTerm = $creditTerm;
    }

    public function getCreditTransactionalFee(): ?Money
    {
        return $this->creditTransactionalFee;
    }

    public function setCreditTransactionalFee(?Money $creditTransactionalFee): void
    {
        $this->creditTransactionalFee = $creditTransactionalFee;
    }

    public function getCreditPromotionalFee(): ?Money
    {
        return $this->creditPromotionalFee;
    }

    public function setCreditPromotionalFee(?Money $creditPromotionalFee): void
    {
        $this->creditPromotionalFee = $creditPromotionalFee;
    }

    public function getAnnualPercentageRate(): ?string
    {
        return $this->annualPercentageRate;
    }

    public function setAnnualPercentageRate(?string $annualPercentageRate): void
    {
        $this->annualPercentageRate = $annualPercentageRate;
    }

    public function getPaymentMethodType(): ?string
    {
        return $this->paymentMethodType;
    }

    public function setPaymentMethodType(?string $paymentMethodType): void
    {
        $this->paymentMethodType = $paymentMethodType;
    }

    public function getInstrumentType(): ?string
    {
        return $this->instrumentType;
    }

    public function setInstrumentType(?string $instrumentType): void
    {
        $this->instrumentType = $instrumentType;
    }

    public function getInstrumentSubType(): ?string
    {
        return $this->instrumentSubType;
    }

    public function setInstrumentSubType(?string $instrumentSubType): void
    {
        $this->instrumentSubType = $instrumentSubType;
    }
}
