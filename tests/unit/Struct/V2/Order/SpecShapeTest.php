<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Struct\V2\Order;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Card\StoredCredential;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Card\StoredCredential\NetworkTransactionReference;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\SupplementaryData;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\SupplementaryData\Card;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\SupplementaryData\Card\LineItem;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\SupplementaryData\Risk;

/**
 * Payloads shaped as checkout_orders_v2 defines them.
 *
 * @internal
 */
#[CoversClass(SupplementaryData::class)]
#[CoversClass(Card::class)]
#[CoversClass(Risk::class)]
#[CoversClass(LineItem::class)]
#[CoversClass(StoredCredential::class)]
#[CoversClass(NetworkTransactionReference::class)]
class SpecShapeTest extends TestCase
{
    public function testSupplementaryDataRoundTrips(): void
    {
        $payload = [
            'card' => [
                'level_2' => ['invoice_id' => 'INV-1', 'tax_total' => ['currency_code' => 'EUR', 'value' => '1.90']],
                'level_3' => ['ships_from_postal_code' => '48624', 'line_items' => [['name' => 'Shirt', 'quantity' => '2']]],
            ],
            'risk' => ['customer' => ['ip_address' => '203.0.113.7']],
        ];

        $data = (new SupplementaryData())->assign($payload);

        $card = $data->getCard();
        static::assertNotNull($card);
        $level3 = $card->getLevel3();
        static::assertNotNull($level3);
        static::assertSame('INV-1', $card->getLevel2()?->getInvoiceId());
        static::assertSame('48624', $level3->getShipsFromPostalCode());
        static::assertSame(2, $level3->getLineItems()->first()?->getQuantity());
        static::assertSame('203.0.113.7', $data->getRisk()->getCustomer()?->getIpAddress());

        $serialized = \json_decode(\json_encode($data, \JSON_THROW_ON_ERROR), true, 512, \JSON_THROW_ON_ERROR);
        static::assertIsArray($serialized);
        static::assertSame(['card', 'risk'], \array_keys($serialized));
        static::assertIsArray($serialized['card']);
        static::assertIsArray($serialized['risk']);
        static::assertSame(['level_2', 'level_3'], \array_keys($serialized['card']));
        static::assertSame(['customer'], \array_keys($serialized['risk']));
    }

    public function testPreviousNetworkTransactionReferenceIsAnObject(): void
    {
        $credential = (new StoredCredential())->assign([
            'previous_network_transaction_reference' => ['id' => '123456789012345', 'network' => 'VISA'],
        ]);

        static::assertSame('123456789012345', $credential->getPreviousNetworkTransactionReference()?->getId());
        static::assertSame('VISA', $credential->getPreviousNetworkTransactionReference()->getNetwork());
        static::assertNull($credential->getPreviousNetworkTransactionReference()->getDate());
    }
}
