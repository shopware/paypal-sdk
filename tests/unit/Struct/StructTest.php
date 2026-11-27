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
use Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Context\ShippingErrorContext;
use Shopware\PayPalSDK\Struct\Collection;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\MerchantIntegrations\Product;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Bank\SepaDebit;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Card\AuthenticationResult;
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
        static::assertSame([], $this->cycleStruct($data));
    }

    /**
     * @param class-string<Struct> $class
     */
    #[DataProvider('provideNullAssignmentsPreserveExistingValues')]
    public function testNullAssignmentsPreserveExistingValues(string $class, string $property, mixed $value): void
    {
        $struct = $class::from([$property => $value]);
        $previous = $struct->getVars()[$property];
        $struct->assign([$property => null]);

        static::assertSame($previous, $struct->getVars()[$property]);
    }

    /**
     * @return iterable<string, array{class-string<Struct>, string, mixed}>
     */
    public static function provideNullAssignmentsPreserveExistingValues(): iterable
    {
        yield 'vetting status' => [Product::class, 'vettingStatus', 'SUBSCRIBED'];
        yield 'restriction reason' => [ShippingErrorContext::class, 'restrictionReason', 'age_verification_required'];
        yield 'liability shift' => [AuthenticationResult::class, 'liabilityShift', 'YES'];
        yield 'IBAN last chars' => [SepaDebit::class, 'ibanLastChars', '1234'];
        yield 'billing address' => [SepaDebit::class, 'billingAddress', ['country_code' => 'DE']];
    }

    public function testEmptyScalarArrayReplacesPreviousValues(): void
    {
        $struct = (new TestStruct())->assign(['scalar_array' => ['old']]);
        $struct->assign(['scalar_array' => []]);

        static::assertSame(['scalar_array' => []], $struct->jsonSerialize());
    }

    public function testAssignEmptyCollection(): void
    {
        $struct = (new TestStruct())->assign(['foo' => []]);

        static::assertInstanceOf(FooCollection::class, $struct->getVars()['foo'] ?? null);
        static::assertSame([], $struct->jsonSerialize());
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
        // an explicitly empty list must still be serialized instead of being omitted like null
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

    public function testSerializeOmitsNullAndKeepsFalsyValues(): void
    {
        $struct = new class extends Struct {
            protected ?string $missing = null;

            protected bool $flag = false;

            protected int $count = 0;

            protected string $zero = '0';

            /** @var string[] */
            protected array $list = [];
        };

        static::assertSame(['flag' => false, 'count' => 0, 'zero' => '0', 'list' => []], $struct->jsonSerialize());
    }

    public function testAssignSkipsArrayForNonArrayProperty(): void
    {
        static::assertEmpty($this->cycleStruct(['id' => ['not' => 'a string']]));
    }

    public function testAssignCachesOnlyCanonicalKeys(): void
    {
        $struct = new class extends Struct {
            protected string $orderId;

            public function getOrderId(): string
            {
                return $this->orderId;
            }
        };
        $struct->assign(['order__id' => 'ORDER-2', 'unknown' => 'value']);
        static::assertSame('ORDER-2', $struct->getOrderId());
        $struct->assign(['order_id' => 'ORDER-1', 'orderId' => 'ORDER-1']);

        $metadata = (new \ReflectionProperty(Struct::class, 'assignMetadata'))->getValue();
        static::assertIsArray($metadata);
        static::assertIsArray($metadata[$struct::class] ?? null);
        static::assertSame(['order_id', 'orderId'], \array_keys($metadata[$struct::class]));
    }

    public function testAssignThrowsForUnionWithStruct(): void
    {
        $struct = new class extends Struct {
            protected Bar|FooCollection $value;
        };

        $this->expectException(\LogicException::class);
        $struct->assign(['value' => ['bar' => 'baz']]);
    }

    public function testAssignThrowsForAbstractStruct(): void
    {
        $struct = new class extends Struct {
            protected ?Struct $value = null;
        };

        $this->expectException(\LogicException::class);
        $struct->assign(['value' => ['bar' => 'baz']]);
    }

    /**
     * {@see Struct::assign()} cannot tell which member of a union an array belongs to.
     */
    public function testStructsDoNotDeclareUnionsWithStructs(): void
    {
        $root = \dirname(__DIR__, 3) . '/src/Struct/';
        $violations = [];

        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root)) as $file) {
            if (!$file instanceof \SplFileInfo || $file->getExtension() !== 'php') {
                continue;
            }

            $class = 'Shopware\PayPalSDK\Struct\\' . \str_replace('/', '\\', \substr($file->getPathname(), \strlen($root), -4));
            if (!\is_a($class, Struct::class, true)) {
                continue;
            }

            foreach ((new \ReflectionClass($class))->getProperties() as $property) {
                $type = $property->getType();
                if (!$type instanceof \ReflectionUnionType || $property->getDeclaringClass()->getName() !== $class) {
                    continue;
                }

                foreach ($type->getTypes() as $member) {
                    if ($member instanceof \ReflectionNamedType && (\is_a($member->getName(), Struct::class, true) || \is_a($member->getName(), Collection::class, true))) {
                        $violations[] = $class . '::$' . $property->getName();
                    }
                }
            }
        }

        static::assertSame([], $violations);
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
