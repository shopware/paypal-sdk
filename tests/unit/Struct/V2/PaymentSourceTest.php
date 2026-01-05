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
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Klarna;
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
                'email' => 'john.doe@example.com',
                'phone' => '+1234567890',
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
        static::assertSame('john.doe@example.com', $klarna->getEmail());
        static::assertSame('+1234567890', $klarna->getPhone());

        $billingAddress = $klarna->getBillingAddress();
        static::assertNotNull($billingAddress);
        static::assertSame('123 Main St', $billingAddress->getAddressLine1());
        static::assertSame('San Jose', $billingAddress->getAdminArea2());
        static::assertSame('CA', $billingAddress->getAdminArea1());
        static::assertSame('95131', $billingAddress->getPostalCode());
        static::assertSame('US', $billingAddress->getCountryCode());

        static::assertArrayHasKey('klarna', $paymentSource->jsonSerialize());
    }

    public function testSwishPaymentSource(): void
    {
        $paymentSource = (new PaymentSource())->assign([
            'swish' => [
                'name' => 'Erik Svensson',
                'countryCode' => 'SE',
                'phone' => '+46701234567',
            ],
        ]);

        $swish = $paymentSource->getSwish();
        static::assertInstanceOf(Swish::class, $swish);
        static::assertSame('Erik Svensson', $swish->getName());
        static::assertSame('SE', $swish->getCountryCode());
        static::assertSame('+46701234567', $swish->getPhone());

        static::assertArrayHasKey('swish', $paymentSource->jsonSerialize());
    }

    public function testAfterpayPaymentSource(): void
    {
        $paymentSource = (new PaymentSource())->assign([
            'afterpay' => [
                'name' => 'Jane Smith',
                'countryCode' => 'AU',
                'email' => 'jane.smith@example.com',
                'phone' => '+61412345678',
                'birthDate' => '1990-05-15',
            ],
        ]);

        $afterpay = $paymentSource->getAfterpay();
        static::assertInstanceOf(Afterpay::class, $afterpay);
        static::assertSame('Jane Smith', $afterpay->getName());
        static::assertSame('AU', $afterpay->getCountryCode());
        static::assertSame('jane.smith@example.com', $afterpay->getEmail());
        static::assertSame('+61412345678', $afterpay->getPhone());
        static::assertSame('1990-05-15', $afterpay->getBirthDate());

        static::assertArrayHasKey('afterpay', $paymentSource->jsonSerialize());
    }
}
