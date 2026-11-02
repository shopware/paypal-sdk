<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Struct\V2;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Struct\V2\CreateOrder;

/**
 * @internal
 */
#[CoversClass(CreateOrder::class)]
class CreateOrderTest extends TestCase
{
    public function testRequestDoesNotSerializeResponseFields(): void
    {
        $request = (new CreateOrder())->assign([
            'id' => 'RESPONSE-ID',
            'status' => 'COMPLETED',
            'create_time' => '2026-01-01T00:00:00Z',
            'links' => [['href' => 'https://example.com', 'rel' => 'self']],
            'intent' => 'AUTHORIZE',
            'purchase_units' => [['amount' => ['currency_code' => 'EUR', 'value' => '10.00']]],
        ]);

        $data = $request->jsonSerialize();

        static::assertSame(['intent', 'purchase_units'], \array_keys($data));
        static::assertSame('AUTHORIZE', $data['intent']);
        static::assertCount(1, $request->getPurchaseUnits());
    }

    /**
     * @param array<mixed> $data
     */
    #[DataProvider('provideInvalidRequestsAreRejected')]
    public function testInvalidRequestsAreRejected(array $data, string $message): void
    {
        $request = (new CreateOrder())->assign($data);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        $request->jsonSerialize();
    }

    /**
     * @return iterable<string, array{array<mixed>, string}>
     */
    public static function provideInvalidRequestsAreRejected(): iterable
    {
        yield 'missing intent' => [[], 'explicit CAPTURE or AUTHORIZE intent'];
        yield 'invalid intent' => [['intent' => 'SALE'], 'explicit CAPTURE or AUTHORIZE intent'];
        yield 'missing units' => [['intent' => 'CAPTURE'], 'at least one purchase unit'];
        yield 'empty units' => [['intent' => 'CAPTURE', 'purchase_units' => []], 'at least one purchase unit'];
    }
}
