<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Struct\V1\Webhook;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Subscription;
use Shopware\PayPalSDK\Struct\V1\Webhook\Event;
use Shopware\PayPalSDK\Struct\V1\Webhook\Resource;
use Shopware\PayPalSDK\Struct\V2\Order;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments\Authorization;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments\Capture;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments\Refund;
use Shopware\PayPalSDK\Struct\V3\PaymentToken;

/**
 * @internal
 */
#[CoversClass(Event::class)]
class EventTest extends TestCase
{
    /**
     * @return iterable<string, array{string, string, class-string<Struct>}>
     */
    public static function provideIdentifyResourceType(): iterable
    {
        yield 'v2 authorization' => ['2.0', 'authorization', Authorization::class];
        yield 'v2 capture' => ['2.0', 'capture', Capture::class];
        yield 'v2 checkout-order' => ['2.0', 'checkout-order', Order::class];
        yield 'v2 refund' => ['2.0', 'refund', Refund::class];
        yield 'v2 subscription' => ['2.0', 'subscription', Subscription::class];
        yield 'v3 payment_token' => ['3.0', 'payment_token', PaymentToken::class];
        yield 'v1 fallback' => ['1.0', 'sale', Resource::class];
    }

    /**
     * @param class-string<Struct> $expectedClass
     */
    #[DataProvider('provideIdentifyResourceType')]
    public function testIdentifyResourceType(string $resourceVersion, string $resourceType, string $expectedClass): void
    {
        $event = Struct::from(Event::class, [
            'id' => 'WH-TEST-123',
            'event_type' => 'TEST.EVENT',
            'summary' => 'Test event',
            'resource_type' => $resourceType,
            'resource_version' => $resourceVersion,
            'resource' => ['id' => 'RESOURCE-123'],
            'create_time' => '2026-01-01T00:00:00Z',
            'event_version' => '1.0',
            'links' => [],
        ]);

        static::assertNotNull($event->getResource());
        static::assertInstanceOf($expectedClass, $event->getResource());
    }

    public function testCheckoutOrderResourceHasId(): void
    {
        $event = Struct::from(Event::class, [
            'id' => 'WH-CHECKOUT-ORDER',
            'event_type' => 'CHECKOUT.ORDER.APPROVED',
            'summary' => 'An order has been approved by buyer',
            'resource_type' => 'checkout-order',
            'resource_version' => '2.0',
            'resource' => [
                'id' => '4UX51220T4035005S',
                'intent' => 'CAPTURE',
                'status' => 'COMPLETED',
                'purchase_units' => [
                    [
                        'reference_id' => 'default',
                        'amount' => [
                            'currency_code' => 'EUR',
                            'value' => '100.00',
                        ],
                    ],
                ],
            ],
            'create_time' => '2026-02-12T11:58:32.634Z',
            'event_version' => '1.0',
            'links' => [],
        ]);

        $resource = $event->getResource();
        static::assertNotNull($resource);
        static::assertInstanceOf(Order::class, $resource);
        static::assertSame('4UX51220T4035005S', $resource->getId());
        static::assertSame('COMPLETED', $resource->getStatus());
    }

    public function testNullResourceForUnknownV2Type(): void
    {
        $event = Struct::from(Event::class, [
            'id' => 'WH-UNKNOWN',
            'event_type' => 'TEST.EVENT',
            'summary' => 'Test event',
            'resource_type' => 'unknown-type',
            'resource_version' => '2.0',
            'resource' => ['id' => 'RESOURCE-123'],
            'create_time' => '2026-01-01T00:00:00Z',
            'event_version' => '1.0',
            'links' => [],
        ]);

        static::assertNull($event->getResource());
    }

    public function testNullResourceWhenResourceIsNull(): void
    {
        $event = Struct::from(Event::class, [
            'id' => 'WH-NULL-RESOURCE',
            'event_type' => 'CHECKOUT.ORDER.APPROVED',
            'summary' => 'An order has been approved by buyer',
            'resource_type' => 'checkout-order',
            'resource_version' => '2.0',
            'resource' => null,
            'create_time' => '2026-02-12T11:58:32.634Z',
            'event_version' => '1.0',
            'links' => [],
        ]);

        static::assertNull($event->getResource());
    }
}
