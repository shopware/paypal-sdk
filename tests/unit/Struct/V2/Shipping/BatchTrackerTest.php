<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Struct\V2\Shipping;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Shipping\Tracker as PurchaseUnitTracker;
use Shopware\PayPalSDK\Struct\V2\Shipping\BatchTracker;
use Shopware\PayPalSDK\Struct\V2\Shipping\BatchTrackerCollection;

/**
 * @internal
 */
#[CoversClass(BatchTracker::class)]
#[CoversClass(BatchTrackerCollection::class)]
class BatchTrackerTest extends TestCase
{
    public function testSerializesBatchTrackerDefaults(): void
    {
        $tracker = (new BatchTracker())->assign([
            'transaction_id' => 'capture-id',
            'tracking_number' => 'tracking-code',
            'carrier' => 'DHL',
            'notify_buyer' => false,
        ]);

        static::assertSame([
            'transaction_id' => 'capture-id',
            'tracking_number' => 'tracking-code',
            'status' => PurchaseUnitTracker::STATUS_SHIPPED,
            'carrier' => 'DHL',
            'carrier_name_other' => null,
            'notify_buyer' => false,
        ], $tracker->jsonSerialize());
    }

    public function testSerializesBatchTrackerCollection(): void
    {
        $tracker = (new BatchTracker())->assign([
            'transaction_id' => 'capture-id',
            'tracking_number' => 'tracking-code',
            'carrier' => 'DHL',
            'notify_buyer' => true,
        ]);

        $trackers = new BatchTrackerCollection([$tracker]);

        static::assertSame([[
            'transaction_id' => 'capture-id',
            'tracking_number' => 'tracking-code',
            'status' => PurchaseUnitTracker::STATUS_SHIPPED,
            'carrier' => 'DHL',
            'carrier_name_other' => null,
            'notify_buyer' => true,
        ]], $trackers->jsonSerialize());
    }
}
