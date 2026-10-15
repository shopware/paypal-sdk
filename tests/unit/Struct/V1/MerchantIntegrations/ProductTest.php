<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Struct\V1\MerchantIntegrations;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Struct\V1\MerchantIntegrations\Product;

/**
 * @internal
 */
#[CoversClass(Product::class)]
class ProductTest extends TestCase
{
    /**
     * @param array<string, mixed> $data
     */
    #[DataProvider('provideVettingStatus')]
    public function testVettingStatus(array $data, ?string $expected): void
    {
        $product = (new Product())->assign(['name' => 'PAYMENT_METHODS', ...$data]);

        static::assertSame($expected, $product->getVettingStatus());
    }

    public static function provideVettingStatus(): \Generator
    {
        yield 'missing' => [[], null];
        yield 'null' => [['vetting_status' => null], null];
        yield 'set' => [['vetting_status' => Product::VETTING_STATUS_SUBSCRIBED], Product::VETTING_STATUS_SUBSCRIBED];
    }
}
