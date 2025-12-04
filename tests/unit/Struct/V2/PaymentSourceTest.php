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
                'name' => 'John Doe',
                'countryCode' => 'US',
                'email' => 'john.doe@example.com',
                'phone' => '+1234567890',
            ],
        ]);

        $klarna = $paymentSource->getKlarna();
        static::assertInstanceOf(Klarna::class, $klarna);
        static::assertEquals('John Doe', $klarna->getName());
        static::assertEquals('US', $klarna->getCountryCode());
        static::assertEquals('john.doe@example.com', $klarna->getEmail());
        static::assertEquals('+1234567890', $klarna->getPhone());

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
        static::assertEquals('Erik Svensson', $swish->getName());
        static::assertEquals('SE', $swish->getCountryCode());
        static::assertEquals('+46701234567', $swish->getPhone());

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
        static::assertEquals('Jane Smith', $afterpay->getName());
        static::assertEquals('AU', $afterpay->getCountryCode());
        static::assertEquals('jane.smith@example.com', $afterpay->getEmail());
        static::assertEquals('+61412345678', $afterpay->getPhone());
        static::assertEquals('1990-05-15', $afterpay->getBirthDate());

        static::assertArrayHasKey('afterpay', $paymentSource->jsonSerialize());
    }
}
