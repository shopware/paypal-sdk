<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Struct;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Tests\Fixture\Struct\TestStruct;
use Shopware\PayPalSDK\Tests\Fixture\Struct\TestStruct\Bar;
use Shopware\PayPalSDK\Tests\Fixture\Struct\TestStruct\Foo;
use Shopware\PayPalSDK\Tests\Fixture\Struct\TestStruct\FooCollection;

/**
 * @internal
 */
#[CoversClass(Struct::class)]
#[UsesClass(TestStruct::class)]
#[UsesClass(Bar::class)]
#[UsesClass(Foo::class)]
#[UsesClass(FooCollection::class)]
class StructTest extends TestCase
{
    public function testAssignScalarValue(): void
    {
        $data = ['id' => 'testId'];
        static::assertSame($data, $this->cycleStruct($data));
    }

    public function testAssignObject(): void
    {
        $data = [
            'bar' => [
                'bar' => 'testBar',
            ],
        ];
        static::assertSame($data, $this->cycleStruct($data));
    }

    public function testAssignScalarArray(): void
    {
        $data = [
            'scalar_array' => [
                'test',
            ],
        ];
        static::assertSame($data, $this->cycleStruct($data));
    }

    public function testAssignCollection(): void
    {
        $data = [
            'foo' => [
                [
                    'foo_baz' => 'fooBazTest',
                ],
            ],
        ];
        static::assertSame($data, $this->cycleStruct($data));
    }

    public function testAssignNestedStructAndCollectionTogether(): void
    {
        $data = [
            'id' => 'testId',
            'bar' => [
                'bar' => 'testBar',
            ],
            'foo' => [
                [
                    'foo_baz' => 'fooBazTest',
                ],
            ],
        ];
        static::assertSame($data, $this->cycleStruct($data));
    }

    public function testAssignDeeplyNestedCollectionOfStructsWithNestedStruct(): void
    {
        $data = [
            'foo' => [
                [
                    'foo_baz' => 'firstFoo',
                    'bar' => [
                        'bar' => 'firstBar',
                    ],
                ],
                [
                    'foo_baz' => 'secondFoo',
                    'bar' => [
                        'bar' => 'secondBar',
                    ],
                ],
            ],
        ];
        static::assertSame($data, $this->cycleStruct($data));
    }

    public function testAssignNull(): void
    {
        $data = ['foo' => null];
        static::assertEmpty($this->cycleStruct($data));
    }

    public function testAssignEmptyArray(): void
    {
        $data = ['foo' => []];
        static::assertEmpty($this->cycleStruct($data));
    }

    public function testAssignNoSetter(): void
    {
        $actual = $this->cycleStruct([
            'no_setter' => 'testValue',
        ]);

        static::assertEmpty($actual);
    }

    public function testSerializeDateTime(): void
    {
        $struct = new class extends Struct {
            protected \DateTimeInterface $createdAt;

            public function setCreatedAt(\DateTimeInterface $createdAt): void
            {
                $this->createdAt = $createdAt;
            }
        };
        $struct->setCreatedAt(new \DateTimeImmutable('2026-01-31T23:59:59+02:00'));

        static::assertSame(['created_at' => '2026-01-31T23:59:59+02:00'], $struct->jsonSerialize());
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private function cycleStruct(array $data): array
    {
        $paypalStruct = new TestStruct();
        $paypalStruct->assign($data);

        $testJsonString = \json_encode($paypalStruct);
        static::assertNotFalse($testJsonString);

        /** @var false|array<string, mixed> paypalStructArray */
        $paypalStructArray = \json_decode($testJsonString, true);
        static::assertIsArray($paypalStructArray);

        static::assertSame($paypalStruct->jsonSerialize(), $paypalStructArray);

        return $paypalStruct->jsonSerialize();
    }
}
