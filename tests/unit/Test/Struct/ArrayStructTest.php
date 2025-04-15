<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Test\Struct\ArrayStruct;

/**
 * @internal
 */
#[CoversClass(ArrayStruct::class)]
class ArrayStructTest extends TestCase
{
    protected ArrayStruct $struct;

    protected function setUp(): void
    {
        $this->struct = (new ArrayStruct())->assign(self::getComplexArray());
    }

    public function testIterator(): void
    {
        foreach ($this->struct as $key => $value) {
            match ($key) {
                'int' => static::assertSame(123, $value),
                'nested' => static::assertInstanceOf(ArrayStruct::class, $value),
                default => null,
            };
        }
    }

    public function testSet(): void
    {
        $this->struct->set('test', ['simple' => self::getSimpleArray()]);

        static::assertTrue($this->struct->has('test'));
        $test = $this->struct->get('test');
        static::assertInstanceOf(ArrayStruct::class, $test);
        static::assertTrue($test->has('simple'));
        $simple = $test->get('simple');
        static::assertInstanceOf(ArrayStruct::class, $simple);
        self::assertSimpleArray($simple);
    }

    public function testRemove(): void
    {
        $this->struct->remove('assoc', 'nested');
        static::assertSimpleArrayKeys($this->struct, ['list']);
    }

    public function testArrayAccess(): void
    {
        static::assertSame(123, $this->struct['int']);
        $this->struct['test'] = ['nested' => self::getSimpleArray()];
        static::assertInstanceOf(ArrayStruct::class, $this->struct['test']);
        static::assertInstanceOf(ArrayStruct::class, $this->struct['test']['nested']);
        static::assertSame(123, $this->struct['test']['nested']['int']);
        unset($this->struct['test']['nested']);
        /** @phpstan-ignore-next-line */
        static::assertNull($this->struct['test']['nested']);
    }

    public function testInOutEquality(): void
    {
        static::assertEquals(self::getComplexArray(), $this->struct->jsonSerialize());
    }

    public function testGetHasKeysStructCount(): void
    {
        self::assertSimpleArray($this->struct);
        static::assertSimpleArrayKeys($this->struct, ['list', 'assoc', 'nested']);

        static::assertTrue($this->struct->has('list'));
        $list = $this->struct->get('list');
        static::assertInstanceOf(ArrayStruct::class, $list);
        static::assertIsList($list->getData());
        static::assertCount(3, $list);
        static::assertEquals([0, 1, 2], $list->getKeys());
        foreach ([0, 1, 2] as $key) {
            $item = $list->get($key);
            static::assertInstanceOf(ArrayStruct::class, $item);
            self::assertSimpleArray($item);
            self::assertSimpleArrayKeys($item);
        }
        static::assertNotNull($this->struct->getStruct('list'));

        static::assertTrue($this->struct->has('assoc'));
        $assoc = $this->struct->get('assoc');
        static::assertInstanceOf(ArrayStruct::class, $assoc);
        self::assertSimpleArray($assoc);
        self::assertSimpleArrayKeys($assoc);
        static::assertNotNull($this->struct->getStruct('assoc'));

        static::assertTrue($this->struct->has('nested'));
        $nested = $this->struct->get('nested');
        static::assertInstanceOf(ArrayStruct::class, $nested);
        static::assertEquals(['nested-2'], $nested->getKeys());
        static::assertNotNull($this->struct->getStruct('nested'));

        static::assertTrue($nested->has('nested-2'));
        $nested2 = $nested->get('nested-2');
        static::assertInstanceOf(ArrayStruct::class, $nested2);
        self::assertSimpleArray($nested2);
        self::assertSimpleArrayKeys($nested2, ['nested-3']);
        static::assertNotNull($nested->getStruct('nested-2'));

        static::assertTrue($nested2->has('nested-3'));
        $nested3 = $nested2->get('nested-3');
        static::assertInstanceOf(ArrayStruct::class, $nested3);
        static::assertEquals(['list'], $nested3->getKeys());

        static::assertTrue($nested3->has('list'));
        $list = $nested3->get('list');
        static::assertInstanceOf(ArrayStruct::class, $list);
        static::assertIsArray($list->getData());
        static::assertCount(3, $list);
        static::assertEquals([1, 3, 7], $list->getKeys());
        foreach ([1, 3, 7] as $key) {
            $item = $list->get($key);
            static::assertInstanceOf(ArrayStruct::class, $item);
            self::assertSimpleArray($item);
            self::assertSimpleArrayKeys($item);
        }
    }

    /**
     * @param list<string> $additional
     */
    protected static function assertSimpleArrayKeys(ArrayStruct $array, array $additional = []): void
    {
        $keys = \array_merge(\array_keys(self::getSimpleArray()), $additional);
        static::assertEquals($keys, $array->getKeys());

        foreach ($keys as $key) {
            static::assertArrayHasKey($key, $array);
            static::assertTrue($array->has($key));
        }
    }

    protected static function assertSimpleArray(ArrayStruct $array): void
    {
        static::assertSame(123, $array->get('int'));
        static::assertSame('some-string', $array->get('string'));
        static::assertSame(0.123, $array->get('float'));
        static::assertTrue($array->get('bool'));
        static::assertEquals(new \stdClass(), $array->get('object'));

        foreach (\array_keys(self::getSimpleArray()) as $key) {
            static::assertNull($array->getStruct($key));
        }
    }

    /**
     * @return array<mixed>
     */
    protected static function getComplexArray(): array
    {
        return [
            ...self::getSimpleArray(),
            'list' => [
                self::getSimpleArray(),
                self::getSimpleArray(),
                self::getSimpleArray(),
            ],
            'assoc' => self::getSimpleArray(),
            'nested' => [
                'nested-2' => [
                    ...self::getSimpleArray(),
                    'nested-3' => [
                        'list' => [
                            1 => self::getSimpleArray(),
                            3 => self::getSimpleArray(),
                            7 => self::getSimpleArray(),
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<mixed>
     */
    protected static function getSimpleArray(): array
    {
        return [
            'int' => 123,
            'string' => 'some-string',
            'float' => 0.123,
            'bool' => true,
            'object' => new \stdClass(),
        ];
    }
}
