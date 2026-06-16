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
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Common\AppSwitchContext;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Common\AppSwitchContext\MobileWebContext;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Common\AppSwitchContext\NativeAppContext;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Common\ExperienceContext;
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
        static::assertSame('John Doe', $klarna->getName());
        static::assertSame('US', $klarna->getCountryCode());
        static::assertSame('john.doe@example.com', $klarna->getEmail());
        static::assertSame('+1234567890', $klarna->getPhone());

        static::assertArrayHasKey('klarna', $paymentSource->jsonSerialize());
    }

    public function testPaypalPaymentSourceWithAppSwitchContext(): void
    {
        $paymentSource = (new PaymentSource())->assign([
            'paypal' => [
                'experience_context' => [
                    'return_url' => 'https://example.com/merchant-app',
                    'cancel_url' => 'https://example.com/merchant-app',
                    'user_action' => ExperienceContext::USER_ACTION_PAY_NOW,
                    'app_switch_context' => [
                        'native_app' => [
                            'os_type' => NativeAppContext::OS_TYPE_IOS,
                            'os_version' => '17.5',
                        ],
                        'mobile_web' => [
                            'return_flow' => MobileWebContext::RETURN_FLOW_AUTO,
                            'buyer_user_agent' => 'Mozilla/5.0',
                        ],
                    ],
                ],
            ],
        ]);

        $paypal = $paymentSource->getPaypal();
        static::assertNotNull($paypal);

        $experienceContext = $paypal->getExperienceContext();
        $appSwitchContext = $experienceContext->getAppSwitchContext();
        static::assertInstanceOf(AppSwitchContext::class, $appSwitchContext);

        $nativeApp = $appSwitchContext->getNativeApp();
        static::assertInstanceOf(NativeAppContext::class, $nativeApp);
        static::assertSame(NativeAppContext::OS_TYPE_IOS, $nativeApp->getOsType());
        static::assertSame('17.5', $nativeApp->getOsVersion());

        $mobileWeb = $appSwitchContext->getMobileWeb();
        static::assertInstanceOf(MobileWebContext::class, $mobileWeb);
        static::assertSame(MobileWebContext::RETURN_FLOW_AUTO, $mobileWeb->getReturnFlow());
        static::assertSame('Mozilla/5.0', $mobileWeb->getBuyerUserAgent());

        $experienceContextPayload = $experienceContext->jsonSerialize();

        static::assertSame([
            'native_app' => [
                'os_type' => NativeAppContext::OS_TYPE_IOS,
                'os_version' => '17.5',
            ],
            'mobile_web' => [
                'return_flow' => MobileWebContext::RETURN_FLOW_AUTO,
                'buyer_user_agent' => 'Mozilla/5.0',
            ],
        ], $experienceContextPayload['app_switch_context']);
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
