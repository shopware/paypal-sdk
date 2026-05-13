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
use Shopware\PayPalSDK\Struct\V1\Webhook\Events\AccountEntities;
use Shopware\PayPalSDK\Struct\V1\Webhook\Events\Dispute;
use Shopware\PayPalSDK\Struct\V1\Webhook\Events\ManagedAccounts;
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
        yield 'v1 managed-accounts' => ['1.0', 'managed-accounts', ManagedAccounts::class];
        yield 'v1 account-entities' => ['1.0', 'account-entities', AccountEntities::class];
        yield 'v1 dispute' => ['1.0', 'dispute', Dispute::class];
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

    public function testDisputeResourcePreservesFields(): void
    {
        $event = Struct::from(Event::class, [
            'id' => 'WH-DISPUTE-CREATED',
            'event_type' => 'CUSTOMER.DISPUTE.CREATED',
            'summary' => 'A dispute has been created',
            'resource_type' => 'dispute',
            'resource_version' => '1.0',
            'resource' => [
                'dispute_id' => 'PP-D-12345',
                'merchant_id' => 'MERCHANT-ABC-123',
                'reason' => 'MERCHANDISE_OR_SERVICE_NOT_RECEIVED',
                'status' => 'WAITING_FOR_SELLER_RESPONSE',
                'dispute_state' => 'REQUIRED_ACTION',
                'dispute_amount' => [
                    'currency_code' => 'USD',
                    'value' => '50.00',
                ],
                'seller_response_due_date' => '2026-02-01T00:00:00Z',
                'dispute_life_cycle_stage' => 'CHARGEBACK',
                'links' => [],
            ],
            'create_time' => '2026-01-15T10:30:00Z',
            'event_version' => '1.0',
            'links' => [],
        ]);

        $resource = $event->getResource();
        static::assertNotNull($resource);
        static::assertInstanceOf(Dispute::class, $resource);
        static::assertSame('PP-D-12345', $resource->getDisputeId());
        static::assertSame('MERCHANT-ABC-123', $resource->getMerchantId());
        static::assertSame('MERCHANDISE_OR_SERVICE_NOT_RECEIVED', $resource->getReason());
        static::assertSame('WAITING_FOR_SELLER_RESPONSE', $resource->getStatus());
        static::assertSame('REQUIRED_ACTION', $resource->getDisputeState());
        static::assertSame('2026-02-01T00:00:00Z', $resource->getSellerResponseDueDate());
        static::assertSame('CHARGEBACK', $resource->getDisputeLifeCycleStage());

        $amount = $resource->getDisputeAmount();
        static::assertNotNull($amount);
        static::assertSame('USD', $amount->getCurrencyCode());
        static::assertSame('50.00', $amount->getValue());
    }

    public function testManagedAccountsResourcePreservesFields(): void
    {
        $event = Struct::from(Event::class, [
            'id' => 'WH-MANAGED-ACCOUNT-CREATED',
            'event_type' => 'CUSTOMER.MANAGED-ACCOUNT.ACCOUNT-CREATED',
            'summary' => 'Managed account created',
            'resource_type' => 'managed-accounts',
            'resource_version' => '1.0',
            'resource' => [
                'external_id' => '019a2b3c-4d5e-7f80-9a0b-1c2d3e4f5a6b',
                'account_id' => 'PAYPAL-MERCHANT-123',
                'links' => [],
            ],
            'create_time' => '2026-01-01T12:00:00Z',
            'event_version' => '1.0',
            'links' => [],
        ]);

        $resource = $event->getResource();
        static::assertNotNull($resource);
        static::assertInstanceOf(ManagedAccounts::class, $resource);
        static::assertSame('019a2b3c-4d5e-7f80-9a0b-1c2d3e4f5a6b', $resource->getExternalId());
        static::assertSame('PAYPAL-MERCHANT-123', $resource->getAccountId());
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

    public function testCaptureResourcePreservesSupplementaryData(): void
    {
        $event = Struct::from(Event::class, [
            'id' => 'WH-CAPTURE-SUPPLEMENTARY',
            'event_type' => 'PAYMENT.CAPTURE.COMPLETED',
            'summary' => 'A capture was completed',
            'resource_type' => 'capture',
            'resource_version' => '2.0',
            'resource' => [
                'id' => 'CAPTURE-123',
                'status' => 'COMPLETED',
                'amount' => [
                    'currency_code' => 'EUR',
                    'value' => '100.00',
                ],
                'supplementary_data' => [
                    'related_ids' => [
                        'order_id' => 'ORDER-456',
                    ],
                ],
            ],
            'create_time' => '2026-02-12T12:00:00Z',
            'event_version' => '1.0',
            'links' => [],
        ]);

        $resource = $event->getResource();
        static::assertNotNull($resource);
        static::assertInstanceOf(Capture::class, $resource);
        static::assertSame('CAPTURE-123', $resource->getId());

        $supplementaryData = $resource->getSupplementaryData();
        static::assertNotNull($supplementaryData);

        $relatedIds = $supplementaryData->getRelatedIds();
        static::assertNotNull($relatedIds);
        static::assertSame('ORDER-456', $relatedIds->getOrderId());
    }

    public function testRefundResourcePreservesSupplementaryData(): void
    {
        $event = Struct::from(Event::class, [
            'id' => 'WH-REFUND-SUPPLEMENTARY',
            'event_type' => 'PAYMENT.CAPTURE.REFUNDED',
            'summary' => 'A capture was refunded',
            'resource_type' => 'refund',
            'resource_version' => '2.0',
            'resource' => [
                'id' => 'REFUND-789',
                'status' => 'COMPLETED',
                'amount' => [
                    'currency_code' => 'EUR',
                    'value' => '50.00',
                ],
                'supplementary_data' => [
                    'related_ids' => [
                        'order_id' => 'ORDER-456',
                        'capture_id' => 'CAPTURE-123',
                    ],
                ],
            ],
            'create_time' => '2026-02-12T12:00:00Z',
            'event_version' => '1.0',
            'links' => [],
        ]);

        $resource = $event->getResource();
        static::assertNotNull($resource);
        static::assertInstanceOf(Refund::class, $resource);
        static::assertSame('REFUND-789', $resource->getId());

        $supplementaryData = $resource->getSupplementaryData();
        static::assertNotNull($supplementaryData);

        $relatedIds = $supplementaryData->getRelatedIds();
        static::assertNotNull($relatedIds);
        static::assertSame('ORDER-456', $relatedIds->getOrderId());
        static::assertSame('CAPTURE-123', $relatedIds->getCaptureId());
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
