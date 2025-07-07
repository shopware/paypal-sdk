<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Struct\V1;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Struct\V1\Common\Address;

/**
 * @internal
 */
#[CoversClass(Address::class)]
class AddressTest extends TestCase
{
    /**
     * @param array<string, string|null> $data
     */
    #[DataProvider('provideJsonSerialize')]
    public function testJsonSerialize(array $data): void
    {
        static::assertSame($data, (new Address())->assign($data)->jsonSerialize());
    }

    public static function provideJsonSerialize(): \Generator
    {
        yield [[
            'city' => 'some-city',
            'state' => null,
            'phone' => '0123456879',
            'line1' => 'some-line-1',
            'line2' => 'some-line-2',
        ]];

        yield [[
            'city' => 'some-city',
            'state' => null,
            'phone' => '0123456879',
            'line2' => 'some-line-2',
        ]];
    }
}
