<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Payment;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Common\Link;
use Shopware\PayPalSDK\Struct\V1\Common\LinkCollection;
use Shopware\PayPalSDK\Struct\V1\Common\Value;
use Shopware\PayPalSDK\Struct\V1\Payment\PaymentInstruction\RecipientBankingInstruction;

#[OA\Schema(schema: 'paypal_v1_payment_payment_instruction')]
class PaymentInstruction extends Struct
{
    public const TYPE_INVOICE = 'PAY_UPON_INVOICE';

    #[OA\Property(type: 'string')]
    protected string $referenceNumber;

    #[OA\Property(ref: RecipientBankingInstruction::class)]
    protected RecipientBankingInstruction $recipientBankingInstruction;

    #[OA\Property(ref: Value::class)]
    protected Value $amount;

    #[OA\Property(type: 'string')]
    protected string $paymentDueDate;

    #[OA\Property(type: 'string')]
    protected string $instructionType;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Link::class))]
    protected LinkCollection $links;

    public function getReferenceNumber(): string
    {
        return $this->referenceNumber;
    }

    public function setReferenceNumber(string $referenceNumber): void
    {
        $this->referenceNumber = $referenceNumber;
    }

    public function getRecipientBankingInstruction(): RecipientBankingInstruction
    {
        return $this->recipientBankingInstruction;
    }

    public function setRecipientBankingInstruction(RecipientBankingInstruction $recipientBankingInstruction): void
    {
        $this->recipientBankingInstruction = $recipientBankingInstruction;
    }

    public function getAmount(): Value
    {
        return $this->amount;
    }

    public function setAmount(Value $amount): void
    {
        $this->amount = $amount;
    }

    public function getPaymentDueDate(): string
    {
        return $this->paymentDueDate;
    }

    public function setPaymentDueDate(string $paymentDueDate): void
    {
        $this->paymentDueDate = $paymentDueDate;
    }

    public function getInstructionType(): string
    {
        return $this->instructionType;
    }

    public function setInstructionType(string $instructionType): void
    {
        $this->instructionType = $instructionType;
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
