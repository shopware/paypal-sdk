<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\AbstractPaymentSource;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Afterpay;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\ApplePay;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Bancontact;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Bank;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Blik;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Boletobancario;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Card;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Eps;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\GooglePay;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Ideal;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Klarna;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Multibanco;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\MyBank;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Oxxo;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\P24;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Paypal;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\PayUponInvoice;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Swish;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Token;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Trustly;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Venmo;

#[OA\Schema(schema: 'paypal_v2_order_payment_source')]
class PaymentSource extends Struct
{
    #[OA\Property(ref: Afterpay::class, nullable: true)]
    protected ?Afterpay $afterpay = null;

    #[OA\Property(ref: ApplePay::class)]
    protected ?ApplePay $applePay = null;

    #[OA\Property(ref: PayUponInvoice::class, nullable: true)]
    protected ?PayUponInvoice $payUponInvoice = null;

    #[OA\Property(ref: Bancontact::class, nullable: true)]
    protected ?Bancontact $bancontact = null;

    #[OA\Property(ref: Blik::class, nullable: true)]
    protected ?Blik $blik = null;

    #[OA\Property(ref: Boletobancario::class, nullable: true)]
    protected ?Boletobancario $boletobancario = null;

    #[OA\Property(ref: Card::class, nullable: true)]
    protected ?Card $card = null;

    #[OA\Property(ref: Eps::class, nullable: true)]
    protected ?Eps $eps = null;

    #[OA\Property(ref: Ideal::class, nullable: true)]
    protected ?Ideal $ideal = null;

    #[OA\Property(ref: Klarna::class, nullable: true)]
    protected ?Klarna $klarna = null;

    #[OA\Property(ref: Multibanco::class, nullable: true)]
    protected ?Multibanco $multibanco = null;

    #[OA\Property(ref: MyBank::class, nullable: true)]
    protected ?MyBank $myBank = null;

    #[OA\Property(ref: Oxxo::class, nullable: true)]
    protected ?Oxxo $oxxo = null;

    #[OA\Property(ref: P24::class, nullable: true)]
    protected ?P24 $p24 = null;

    #[OA\Property(ref: Paypal::class, nullable: true)]
    protected ?Paypal $paypal = null;

    #[OA\Property(ref: Swish::class, nullable: true)]
    protected ?Swish $swish = null;

    #[OA\Property(ref: Token::class, nullable: true)]
    protected ?Token $token = null;

    #[OA\Property(ref: Trustly::class, nullable: true)]
    protected ?Trustly $trustly = null;

    #[OA\Property(ref: GooglePay::class, nullable: true)]
    protected ?GooglePay $googlePay = null;

    #[OA\Property(ref: Venmo::class, nullable: true)]
    protected ?Venmo $venmo = null;

    #[OA\Property(ref: Bank::class, nullable: true)]
    protected ?Bank $bank = null;

    public function getAfterpay(): ?Afterpay
    {
        return $this->afterpay;
    }

    public function setAfterpay(?Afterpay $afterpay): void
    {
        $this->afterpay = $afterpay;
    }

    public function getApplePay(): ?ApplePay
    {
        return $this->applePay;
    }

    public function setApplePay(?ApplePay $applePay): void
    {
        $this->applePay = $applePay;
    }

    public function getPayUponInvoice(): ?PayUponInvoice
    {
        return $this->payUponInvoice;
    }

    public function setPayUponInvoice(?PayUponInvoice $payUponInvoice): void
    {
        $this->payUponInvoice = $payUponInvoice;
    }

    public function getBancontact(): ?Bancontact
    {
        return $this->bancontact;
    }

    public function setBancontact(?Bancontact $bancontact): void
    {
        $this->bancontact = $bancontact;
    }

    public function getBlik(): ?Blik
    {
        return $this->blik;
    }

    public function setBlik(?Blik $blik): void
    {
        $this->blik = $blik;
    }

    public function getBoletobancario(): ?Boletobancario
    {
        return $this->boletobancario;
    }

    public function setBoletobancario(?Boletobancario $boletobancario): void
    {
        $this->boletobancario = $boletobancario;
    }

    public function getCard(): ?Card
    {
        return $this->card;
    }

    public function setCard(?Card $card): void
    {
        $this->card = $card;
    }

    public function getEps(): ?Eps
    {
        return $this->eps;
    }

    public function setEps(?Eps $eps): void
    {
        $this->eps = $eps;
    }

    public function getIdeal(): ?Ideal
    {
        return $this->ideal;
    }

    public function setIdeal(?Ideal $ideal): void
    {
        $this->ideal = $ideal;
    }

    public function getKlarna(): ?Klarna
    {
        return $this->klarna;
    }

    public function setKlarna(?Klarna $klarna): void
    {
        $this->klarna = $klarna;
    }

    public function getMultibanco(): ?Multibanco
    {
        return $this->multibanco;
    }

    public function setMultibanco(?Multibanco $multibanco): void
    {
        $this->multibanco = $multibanco;
    }

    public function getMyBank(): ?MyBank
    {
        return $this->myBank;
    }

    public function setMyBank(?MyBank $myBank): void
    {
        $this->myBank = $myBank;
    }

    public function getOxxo(): ?Oxxo
    {
        return $this->oxxo;
    }

    public function setOxxo(?Oxxo $oxxo): void
    {
        $this->oxxo = $oxxo;
    }

    public function getP24(): ?P24
    {
        return $this->p24;
    }

    public function setP24(?P24 $p24): void
    {
        $this->p24 = $p24;
    }

    public function getPaypal(): ?Paypal
    {
        return $this->paypal;
    }

    public function setPaypal(?Paypal $paypal): void
    {
        $this->paypal = $paypal;
    }

    public function getSwish(): ?Swish
    {
        return $this->swish;
    }

    public function setSwish(?Swish $swish): void
    {
        $this->swish = $swish;
    }

    public function getToken(): ?Token
    {
        return $this->token;
    }

    public function setToken(?Token $token): void
    {
        $this->token = $token;
    }

    public function getTrustly(): ?Trustly
    {
        return $this->trustly;
    }

    public function setTrustly(?Trustly $trustly): void
    {
        $this->trustly = $trustly;
    }

    public function getGooglePay(): ?GooglePay
    {
        return $this->googlePay;
    }

    public function setGooglePay(?GooglePay $googlePay): void
    {
        $this->googlePay = $googlePay;
    }

    public function getVenmo(): ?Venmo
    {
        return $this->venmo;
    }

    public function setVenmo(?Venmo $venmo): void
    {
        $this->venmo = $venmo;
    }

    public function getBank(): ?Bank
    {
        return $this->bank;
    }

    public function setBank(?Bank $bank): void
    {
        $this->bank = $bank;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return \array_filter([
            ...parent::jsonSerialize(),
            'p_2_4' => null,
            'p24' => $this->p24,
        ]);
    }

    /**
     * @template T
     *
     * @param class-string<T> $expectedType
     *
     * @return (T&AbstractPaymentSource)|null
     */
    public function first(string $expectedType = AbstractPaymentSource::class): ?AbstractPaymentSource
    {
        foreach ($this->getVars() as $paymentSource) {
            if ($paymentSource instanceof Bank) {
                foreach ($paymentSource->getVars() as $bankPaymentSource) {
                    if ($bankPaymentSource instanceof $expectedType && $bankPaymentSource instanceof AbstractPaymentSource) {
                        return $bankPaymentSource;
                    }
                }
            }

            if ($paymentSource instanceof $expectedType && $paymentSource instanceof AbstractPaymentSource) {
                return $paymentSource;
            }
        }

        return null;
    }
}
