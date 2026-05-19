<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Util;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Util\DateTimeFormatter;

/**
 * @internal
 */
#[CoversClass(DateTimeFormatter::class)]
class DateTimeFormatterTest extends TestCase
{
    public function testFormatQueryDateTimeWithUtcTimezone(): void
    {
        static::assertSame(
            '2026-01-01T00:00:00Z',
            DateTimeFormatter::formatQueryDateTime(new \DateTimeImmutable('2026-01-01T00:00:00+00:00')),
        );
    }

    public function testFormatQueryDateTimeWithOffsetTimezone(): void
    {
        static::assertSame(
            '2026-01-31T23:59:59+02:00',
            DateTimeFormatter::formatQueryDateTime(new \DateTimeImmutable('2026-01-31T23:59:59+02:00')),
        );
    }
}
