<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Struct\V1;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Struct\V1\MerchantIntegrations;

/**
 * @internal
 */
#[CoversClass(MerchantIntegrations::class)]
class MerchantIntegrationsTest extends TestCase
{
    public function testMissingProducts(): void
    {
        $integrations = (new MerchantIntegrations())->assign(['merchant_id' => 'some-merchant-id']);

        static::assertCount(0, $integrations->getProducts());
        static::assertNull($integrations->getSpecificProduct('PAYMENT_METHODS'));
        static::assertArrayNotHasKey('products', $integrations->jsonSerialize());
    }

    public function testEmptyProducts(): void
    {
        $integrations = (new MerchantIntegrations())->assign(['products' => []]);

        static::assertCount(0, $integrations->getProducts());
        static::assertNull($integrations->getSpecificProduct('PAYMENT_METHODS'));
        static::assertSame([], $integrations->jsonSerialize()['products']);
    }

    public function testSpecificProduct(): void
    {
        $integrations = (new MerchantIntegrations())->assign([
            'products' => [
                ['name' => 'PPCP_CUSTOM', 'vetting_status' => 'SUBSCRIBED'],
                ['name' => 'PAYMENT_METHODS'],
            ],
        ]);

        $product = $integrations->getSpecificProduct('PAYMENT_METHODS');
        static::assertNotNull($product);
        static::assertNull($product->getVettingStatus());
        static::assertNull($integrations->getSpecificProduct('ADVANCED_VAULTING'));
    }
}
