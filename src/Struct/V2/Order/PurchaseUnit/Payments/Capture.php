<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments\Capture\ProcessorResponse;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments\Capture\SellerReceivableBreakdown;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments\Common\SellerProtection;

#[OA\Schema(schema: 'paypal_v2_order_purchase_unit_payments_capture')]
class Capture extends Payment
{
    #[OA\Property(type: 'string', nullable: true, maxLength: self::MAX_LENGTH_INVOICE_ID)]
    protected ?string $invoiceId = null;

    #[OA\Property(type: 'string', nullable: true, maxLength: self::MAX_LENGTH_NOTE_TO_PAYER)]
    protected ?string $noteToPayer = null;

    #[OA\Property(ref: SellerProtection::class)]
    protected SellerProtection $sellerProtection;

    #[OA\Property(type: 'boolean')]
    protected bool $finalCapture;

    #[OA\Property(ref: SellerReceivableBreakdown::class)]
    protected SellerReceivableBreakdown $sellerReceivableBreakdown;

    #[OA\Property(ref: ProcessorResponse::class)]
    protected ProcessorResponse $processorResponse;

    #[OA\Property(type: 'string')]
    protected string $disbursementMode;

    public function getInvoiceId(): ?string
    {
        return $this->invoiceId;
    }

    /**
     * @throws \LengthException if given parameter is too long
     */
    public function setInvoiceId(?string $invoiceId): void
    {
        if ($invoiceId !== null && \mb_strlen($invoiceId) > self::MAX_LENGTH_INVOICE_ID) {
            throw new \LengthException(
                \sprintf('%s::$invoiceId must not be longer than %s characters', self::class, self::MAX_LENGTH_INVOICE_ID)
            );
        }

        $this->invoiceId = $invoiceId;
    }

    public function getNoteToPayer(): ?string
    {
        return $this->noteToPayer;
    }

    /**
     * @throws \LengthException if given parameter is too long
     */
    public function setNoteToPayer(?string $noteToPayer): void
    {
        if ($noteToPayer !== null && \mb_strlen($noteToPayer) > self::MAX_LENGTH_NOTE_TO_PAYER) {
            throw new \LengthException(
                \sprintf('%s::$invoiceId must not be longer than %s characters', self::class, self::MAX_LENGTH_NOTE_TO_PAYER)
            );
        }

        $this->noteToPayer = $noteToPayer;
    }

    public function getSellerProtection(): SellerProtection
    {
        return $this->sellerProtection;
    }

    public function setSellerProtection(SellerProtection $sellerProtection): void
    {
        $this->sellerProtection = $sellerProtection;
    }

    public function isFinalCapture(): bool
    {
        return $this->finalCapture;
    }

    public function setFinalCapture(bool $finalCapture): void
    {
        $this->finalCapture = $finalCapture;
    }

    public function getSellerReceivableBreakdown(): SellerReceivableBreakdown
    {
        return $this->sellerReceivableBreakdown;
    }

    public function setSellerReceivableBreakdown(SellerReceivableBreakdown $sellerReceivableBreakdown): void
    {
        $this->sellerReceivableBreakdown = $sellerReceivableBreakdown;
    }

    public function getProcessorResponse(): ProcessorResponse
    {
        return $this->processorResponse;
    }

    public function setProcessorResponse(ProcessorResponse $processorResponse): void
    {
        $this->processorResponse = $processorResponse;
    }

    public function getDisbursementMode(): string
    {
        return $this->disbursementMode;
    }

    public function setDisbursementMode(string $disbursementMode): void
    {
        $this->disbursementMode = $disbursementMode;
    }
}
