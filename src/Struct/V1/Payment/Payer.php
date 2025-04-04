<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Payment;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Payment\Payer\PayerInfo;

#[OA\Schema(schema: 'paypal_v1_payment_payer')]
class Payer extends Struct
{
    #[OA\Property(type: 'string')]
    protected string $paymentMethod;

    #[OA\Property(type: 'string')]
    protected string $status;

    #[OA\Property(ref: PayerInfo::class)]
    protected PayerInfo $payerInfo;

    #[OA\Property(type: 'string')]
    protected string $externalSelectedFundingInstrumentType;

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(string $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getPayerInfo(): PayerInfo
    {
        return $this->payerInfo;
    }

    public function setPayerInfo(PayerInfo $payerInfo): void
    {
        $this->payerInfo = $payerInfo;
    }

    public function getExternalSelectedFundingInstrumentType(): string
    {
        return $this->externalSelectedFundingInstrumentType;
    }

    public function setExternalSelectedFundingInstrumentType(string $externalSelectedFundingInstrumentType): void
    {
        $this->externalSelectedFundingInstrumentType = $externalSelectedFundingInstrumentType;
    }
}
