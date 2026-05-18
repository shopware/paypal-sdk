<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Common;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Common\Attributes\Customer;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Common\Attributes\Mandate;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Common\Attributes\Token;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Common\Attributes\Vault;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Common\Attributes\Verification;

#[OA\Schema(schema: 'paypal_v2_order_payment_source_common_attributes')]
class Attributes extends Struct
{
    #[OA\Property(ref: Vault::class)]
    protected ?Vault $vault = null;

    #[OA\Property(ref: Mandate::class)]
    protected ?Mandate $mandate = null;

    #[OA\Property(ref: Token::class)]
    protected ?Token $token = null;

    #[OA\Property(ref: Customer::class)]
    protected ?Customer $customer = null;

    #[OA\Property(ref: Verification::class)]
    protected ?Verification $verification = null;

    public function getVault(): ?Vault
    {
        return $this->vault;
    }

    public function setVault(?Vault $vault): void
    {
        $this->vault = $vault;
    }

    public function getMandate(): ?Mandate
    {
        return $this->mandate;
    }

    public function setMandate(?Mandate $mandate): void
    {
        $this->mandate = $mandate;
    }

    public function getToken(): ?Token
    {
        return $this->token;
    }

    public function setToken(?Token $token): void
    {
        $this->token = $token;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): void
    {
        $this->customer = $customer;
    }

    public function getVerification(): ?Verification
    {
        return $this->verification;
    }

    public function setVerification(?Verification $verification): void
    {
        $this->verification = $verification;
    }
}
