<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Bank;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\V2\Common\Address;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\AbstractPaymentSource;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Common\Attributes;

#[OA\Schema(schema: 'paypal_v2_order_payment_source_bank_sepa_debit')]
class SepaDebit extends AbstractPaymentSource
{
    #[OA\Property(type: 'string')]
    protected string $iban;

    #[OA\Property(type: 'string')]
    protected string $accountHolderName;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $ibanLastChars = null;

    #[OA\Property(ref: Address::class, nullable: true)]
    protected ?Address $billingAddress = null;

    #[OA\Property(ref: Attributes::class, nullable: true)]
    protected ?Attributes $attributes = null;

    public function getIban(): string
    {
        return $this->iban;
    }

    public function setIban(string $iban): void
    {
        $this->iban = $iban;
    }

    public function getIbanLastChars(): ?string
    {
        return $this->ibanLastChars;
    }

    public function setIbanLastChars(string $ibanLastChars): void
    {
        $this->ibanLastChars = $ibanLastChars;
    }

    public function getAccountHolderName(): string
    {
        return $this->accountHolderName;
    }

    public function setAccountHolderName(string $accountHolderName): void
    {
        $this->accountHolderName = $accountHolderName;
    }

    public function getBillingAddress(): ?Address
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(Address $billingAddress): void
    {
        $this->billingAddress = $billingAddress;
    }

    public function getAttributes(): ?Attributes
    {
        return $this->attributes;
    }

    public function setAttributes(?Attributes $attributes): void
    {
        $this->attributes = $attributes;
    }
}
