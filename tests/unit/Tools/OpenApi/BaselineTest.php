<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Tools\OpenApi;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Struct\V2\Order;
use Shopware\PayPalSDK\Tools\OpenApi\Baseline;
use Shopware\PayPalSDK\Tools\OpenApi\Finding;

/**
 * @internal
 */
#[CoversClass(Baseline::class)]
#[CoversClass(Finding::class)]
class BaselineTest extends TestCase
{
    public function testUpdateKeepsKnownReasons(): void
    {
        $known = new Finding(Finding::ENUM, Order::class, 'intent', 'spec value X is missing', '+X');
        $new = new Finding(Finding::SDK_ONLY, Order::class, 'foo', 'not defined by order');

        $baseline = (new Baseline(['enum V2\Order::$intent +X' => 'not offered', 'enum V2\Order::$intent +Y' => 'gone']))->update([$known, $new]);

        static::assertSame(['enum V2\Order::$intent +X' => 'not offered', 'sdk-only V2\Order::$foo' => Baseline::TODO], $baseline->entries);
    }

    public function testStaleEntries(): void
    {
        $baseline = new Baseline(['sdk-only V2\Order::$foo' => 'a', 'sdk-only V2\Order::$bar' => 'b']);

        static::assertSame(['sdk-only V2\Order::$bar'], $baseline->stale([new Finding(Finding::SDK_ONLY, Order::class, 'foo', '')]));
    }

    public function testWritesWhatItReads(): void
    {
        $file = sys_get_temp_dir() . '/baseline-' . bin2hex(random_bytes(4)) . '.php';
        $baseline = new Baseline(['sdk-only V2\Order::$foo' => 'it\'s "quoted" \\', 'enum V2\Order::$intent +X' => 'b']);

        try {
            $baseline->write($file);
            $read = Baseline::read($file);
        } finally {
            @unlink($file);
        }

        static::assertSame(['enum V2\Order::$intent +X' => 'b', 'sdk-only V2\Order::$foo' => 'it\'s "quoted" \\'], $read->entries);
    }
}
