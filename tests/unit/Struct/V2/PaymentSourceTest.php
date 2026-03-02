<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Struct\V1;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Afterpay;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Common\ExperienceContext;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Klarna;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Klarna\AuthorizationContext;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\P24;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Swish;

/**
 * @internal
 */
#[CoversClass(PaymentSource::class)]
class PaymentSourceTest extends TestCase
{
    public function testJsonSerialize(): void
    {
        $paymentSource = (new PaymentSource())->assign([
            'p24' => ['email' => 'test@example.com'],
        ]);

        static::assertEquals([
            'p24' => (new P24())->assign(['email' => 'test@example.com']),
        ], $paymentSource->jsonSerialize());
    }

    public function testKlarnaPaymentSource(): void
    {
        $paymentSource = (new PaymentSource())->assign([
            'klarna' => [
                'name' => [
                    'givenName' => 'John',
                    'surname' => 'Doe',
                ],
                'emailAddress' => 'john.doe@example.com',
                'phone' => '+1234567890',
                'countryCode' => 'US',
                'billingAddress' => [
                    'addressLine1' => '123 Main St',
                    'adminArea2' => 'San Jose',
                    'adminArea1' => 'CA',
                    'postalCode' => '95131',
                    'countryCode' => 'US',
                ],
            ],
        ]);

        $klarna = $paymentSource->getKlarna();
        static::assertInstanceOf(Klarna::class, $klarna);
        static::assertSame('John', $klarna->getName()->getGivenName());
        static::assertSame('Doe', $klarna->getName()->getSurname());
        static::assertSame('john.doe@example.com', $klarna->getEmailAddress());
        static::assertSame('+1234567890', $klarna->getPhone());
        static::assertSame('US', $klarna->getCountryCode());

        $billingAddress = $klarna->getBillingAddress();
        static::assertNotNull($billingAddress);
        static::assertSame('123 Main St', $billingAddress->getAddressLine1());
        static::assertSame('San Jose', $billingAddress->getAdminArea2());
        static::assertSame('CA', $billingAddress->getAdminArea1());
        static::assertSame('95131', $billingAddress->getPostalCode());
        static::assertSame('US', $billingAddress->getCountryCode());

        static::assertArrayHasKey('klarna', $paymentSource->jsonSerialize());
    }

    public function testKlarnaPaymentSourceAdminArea1Fallback(): void
    {
        $paymentSource = (new PaymentSource())->assign([
            'klarna' => [
                'name' => [
                    'givenName' => 'John',
                    'surname' => 'Doe',
                ],
                'emailAddress' => 'john.doe@example.com',
                'phone' => '+1234567890',
                'countryCode' => 'DE',
                'billingAddress' => [
                    'addressLine1' => '123 Main St',
                    'adminArea2' => 'Berlin',
                    'postalCode' => '10115',
                    'countryCode' => 'DE',
                ],
            ],
        ]);

        $klarna = $paymentSource->getKlarna();
        static::assertInstanceOf(Klarna::class, $klarna);

        $billingAddress = $klarna->getBillingAddress();
        static::assertNotNull($billingAddress);
        static::assertSame('Berlin', $billingAddress->getAdminArea1());
        static::assertSame('Berlin', $billingAddress->getAdminArea2());
    }

    public function testKlarnaPaymentSourceWithAuthorizationContext(): void
    {
        $paymentSource = (new PaymentSource())->assign([
            'klarna' => [
                'name' => [
                    'givenName' => 'John',
                    'surname' => 'Doe',
                ],
                'emailAddress' => 'john.doe@example.com',
                'phone' => '+1234567890',
                'countryCode' => 'US',
                'authorizationContext' => [
                    'authorizationExpiry' => '2025-12-31T23:59:59Z',
                ],
            ],
        ]);

        $klarna = $paymentSource->getKlarna();
        static::assertInstanceOf(Klarna::class, $klarna);

        $authorizationContext = $klarna->getAuthorizationContext();
        static::assertInstanceOf(AuthorizationContext::class, $authorizationContext);
        static::assertSame('2025-12-31T23:59:59Z', $authorizationContext->getAuthorizationExpiry());
    }

    public function testKlarnaPaymentSourceWithExperienceContext(): void
    {
        $paymentSource = (new PaymentSource())->assign([
            'klarna' => [
                'name' => [
                    'givenName' => 'John',
                    'surname' => 'Doe',
                ],
                'emailAddress' => 'john.doe@example.com',
                'phone' => '+1234567890',
                'countryCode' => 'US',
                'experienceContext' => [
                    'locale' => 'en-US',
                    'brandName' => 'Test Brand',
                    'returnUrl' => 'https://example.com/return',
                    'cancelUrl' => 'https://example.com/cancel',
                    'acquiringChannel' => ExperienceContext::ACQUIRING_CHANNEL_ECOMMERCE,
                ],
            ],
        ]);

        $klarna = $paymentSource->getKlarna();
        static::assertInstanceOf(Klarna::class, $klarna);

        $experienceContext = $klarna->getExperienceContext();
        static::assertSame('en-US', $experienceContext->getLocale());
        static::assertSame('Test Brand', $experienceContext->getBrandName());
        static::assertSame('https://example.com/return', $experienceContext->getReturnUrl());
        static::assertSame('https://example.com/cancel', $experienceContext->getCancelUrl());
        static::assertSame(ExperienceContext::ACQUIRING_CHANNEL_ECOMMERCE, $experienceContext->getAcquiringChannel());
    }

    public function testSwishPaymentSource(): void
    {
        $paymentSource = (new PaymentSource())->assign([
            'swish' => [
                'name' => 'John Doe',
                'countryCode' => 'SE',
                'phone' => '+46701234567',
            ],
        ]);

        $swish = $paymentSource->getSwish();
        static::assertInstanceOf(Swish::class, $swish);
        static::assertSame('John Doe', $swish->getName());
        static::assertSame('SE', $swish->getCountryCode());
        static::assertSame('+46701234567', $swish->getPhone());

        static::assertArrayHasKey('swish', $paymentSource->jsonSerialize());
    }

    public function testAfterpayPaymentSource(): void
    {
        $paymentSource = (new PaymentSource())->assign([
            'afterpay' => [
                'name' => [
                    'givenName' => 'Jane',
                    'surname' => 'Smith',
                ],
                'emailAddress' => 'jane.smith@example.com',
                'phone' => '+61412345678',
                'birthDate' => '1990-05-15',
                'countryCode' => 'AU',
                'billingAddress' => [
                    'addressLine1' => '456 Test St',
                    'adminArea2' => 'Sydney',
                    'adminArea1' => 'NSW',
                    'postalCode' => '2000',
                    'countryCode' => 'AU',
                ],
            ],
        ]);

        $afterpay = $paymentSource->getAfterpay();
        static::assertInstanceOf(Afterpay::class, $afterpay);
        static::assertSame('Jane', $afterpay->getName()->getGivenName());
        static::assertSame('Smith', $afterpay->getName()->getSurname());
        static::assertSame('jane.smith@example.com', $afterpay->getEmailAddress());
        static::assertSame('+61412345678', $afterpay->getPhone());
        static::assertSame('1990-05-15', $afterpay->getBirthDate());
        static::assertSame('AU', $afterpay->getCountryCode());

        $billingAddress = $afterpay->getBillingAddress();
        static::assertNotNull($billingAddress);
        static::assertSame('456 Test St', $billingAddress->getAddressLine1());
        static::assertSame('Sydney', $billingAddress->getAdminArea2());
        static::assertSame('NSW', $billingAddress->getAdminArea1());
        static::assertSame('2000', $billingAddress->getPostalCode());
        static::assertSame('AU', $billingAddress->getCountryCode());

        static::assertArrayHasKey('afterpay', $paymentSource->jsonSerialize());
    }
}
