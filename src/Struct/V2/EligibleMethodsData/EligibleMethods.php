<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\EligibleMethodsData;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\EligibleMethodsData\EligibleMethods\AdvancedCards;
use Shopware\PayPalSDK\Struct\V2\EligibleMethodsData\EligibleMethods\ApplePay;
use Shopware\PayPalSDK\Struct\V2\EligibleMethodsData\EligibleMethods\Bancontact;
use Shopware\PayPalSDK\Struct\V2\EligibleMethodsData\EligibleMethods\Bizum;
use Shopware\PayPalSDK\Struct\V2\EligibleMethodsData\EligibleMethods\Blik;
use Shopware\PayPalSDK\Struct\V2\EligibleMethodsData\EligibleMethods\Eps;
use Shopware\PayPalSDK\Struct\V2\EligibleMethodsData\EligibleMethods\GooglePay;
use Shopware\PayPalSDK\Struct\V2\EligibleMethodsData\EligibleMethods\Ideal;
use Shopware\PayPalSDK\Struct\V2\EligibleMethodsData\EligibleMethods\Klarna;
use Shopware\PayPalSDK\Struct\V2\EligibleMethodsData\EligibleMethods\P24;
use Shopware\PayPalSDK\Struct\V2\EligibleMethodsData\EligibleMethods\Paypal;
use Shopware\PayPalSDK\Struct\V2\EligibleMethodsData\EligibleMethods\PaypalPayLater;

/**
 * @experimental
 */
#[OA\Schema(schema: 'paypal_v2_eligible_methods_data_eligible_methods')]
class EligibleMethods extends Struct
{
    #[OA\Property(ref: Paypal::class)]
    protected ?Paypal $paypal = null;

    #[OA\Property(ref: PaypalPayLater::class)]
    protected ?PaypalPayLater $paypalPayLater = null;

    #[OA\Property(ref: ApplePay::class)]
    protected ?ApplePay $applePay = null;

    #[OA\Property(ref: GooglePay::class)]
    protected ?GooglePay $googlePay = null;

    #[OA\Property(ref: AdvancedCards::class)]
    protected ?AdvancedCards $advancedCards = null;

    #[OA\Property(ref: Eps::class)]
    protected ?Eps $eps = null;

    #[OA\Property(ref: P24::class)]
    protected ?P24 $p24 = null;

    #[OA\Property(ref: Blik::class)]
    protected ?Blik $blik = null;

    #[OA\Property(ref: Ideal::class)]
    protected ?Ideal $ideal = null;

    #[OA\Property(ref: Bizum::class)]
    protected ?Bizum $bizum = null;

    #[OA\Property(ref: Bancontact::class)]
    protected ?Bancontact $bancontact = null;

    #[OA\Property(ref: Klarna::class)]
    protected ?Klarna $klarna = null;

    public function getPayPal(): ?Paypal
    {
        return $this->paypal;
    }

    public function setPayPal(?Paypal $paypal): void
    {
        $this->paypal = $paypal;
    }

    public function getPayPalPayLater(): ?PaypalPayLater
    {
        return $this->paypalPayLater;
    }

    public function setPayPalPayLater(?PaypalPayLater $paypalPayLater): void
    {
        $this->paypalPayLater = $paypalPayLater;
    }

    public function getApplePay(): ?ApplePay
    {
        return $this->applePay;
    }

    public function setApplePay(?ApplePay $applePay): void
    {
        $this->applePay = $applePay;
    }

    public function getGooglePay(): ?GooglePay
    {
        return $this->googlePay;
    }

    public function setGooglePay(?GooglePay $googlePay): void
    {
        $this->googlePay = $googlePay;
    }

    public function getAdvancedCards(): ?AdvancedCards
    {
        return $this->advancedCards;
    }

    public function setAdvancedCards(?AdvancedCards $advancedCards): void
    {
        $this->advancedCards = $advancedCards;
    }

    public function getEps(): ?Eps
    {
        return $this->eps;
    }

    public function setEps(?Eps $eps): void
    {
        $this->eps = $eps;
    }

    public function getP24(): ?P24
    {
        return $this->p24;
    }

    public function setP24(?P24 $p24): void
    {
        $this->p24 = $p24;
    }

    public function getBlik(): ?Blik
    {
        return $this->blik;
    }

    public function setBlik(?Blik $blik): void
    {
        $this->blik = $blik;
    }

    public function getIdeal(): ?Ideal
    {
        return $this->ideal;
    }

    public function setIdeal(?Ideal $ideal): void
    {
        $this->ideal = $ideal;
    }

    public function getBizum(): ?Bizum
    {
        return $this->bizum;
    }

    public function setBizum(?Bizum $bizum): void
    {
        $this->bizum = $bizum;
    }

    public function getBancontact(): ?Bancontact
    {
        return $this->bancontact;
    }

    public function setBancontact(?Bancontact $bancontact): void
    {
        $this->bancontact = $bancontact;
    }

    public function getKlarna(): ?Klarna
    {
        return $this->klarna;
    }

    public function setKlarna(?Klarna $klarna): void
    {
        $this->klarna = $klarna;
    }

    /**
     * @param class-string<Struct> $methodClass
     */
    public function has(string $methodClass): bool
    {
        foreach (\get_object_vars($this) as $eligibleMethod) {
            if (\is_object($eligibleMethod) && $eligibleMethod::class === $methodClass) {
                return true;
            }
        }

        return false;
    }
}
