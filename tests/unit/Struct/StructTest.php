<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Struct;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
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

    public function testAssignEmptyCollection(): void
    {
        $struct = (new TestStruct())->assign(['foo' => []]);

        static::assertInstanceOf(FooCollection::class, $struct->getVars()['foo'] ?? null);
        static::assertSame(['foo' => []], $struct->jsonSerialize());
    }

    /**
     * An empty array fits a collection or a plain array property. Anywhere else it would
     * fabricate a value the response never carried, or fail the setter's declared type.
     */
    #[DataProvider('provideAssignEmptyArrayToNonArrayProperty')]
    public function testAssignEmptyArrayToNonArrayProperty(string $key): void
    {
        static::assertEmpty($this->cycleStruct([$key => []]));
    }

    public static function provideAssignEmptyArrayToNonArrayProperty(): \Generator
    {
        yield 'struct' => ['bar'];
        yield 'string' => ['id'];
        yield 'object' => ['not_existing_class'];
        yield 'union without an array member' => ['scalar_union'];
    }

    public function testAssignEmptyNullableCollection(): void
    {
        // the SDK declares most of its collections nullable, e.g. Error::$details
        $struct = new class extends Struct {
            protected ?FooCollection $foo = null;
        };
        $struct->assign(['foo' => []]);

        static::assertInstanceOf(FooCollection::class, $struct->getVars()['foo'] ?? null);
    }

    public function testAssignEmptyScalarArray(): void
    {
        $data = ['scalar_array' => []];
        static::assertSame($data, $this->cycleStruct($data));
    }

    public function testAssignEmptyNullableScalarArray(): void
    {
        // the SDK declares its scalar arrays nullable with a null default, e.g. DataErrorContext::$allowedValues,
        // so the property is always serialized and an explicitly empty list must not come back out as null
        $struct = new class extends Struct {
            /** @var string[]|null */
            protected ?array $allowedValues = null;
        };
        $struct->assign(['allowed_values' => []]);

        static::assertSame(['allowed_values' => []], $struct->jsonSerialize());
    }

    public function testAssignEmptyUnionArray(): void
    {
        // e.g. Patch::$value, where an empty array is a valid RFC 6902 replacement value
        $data = ['union_array' => []];
        static::assertSame($data, $this->cycleStruct($data));
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
