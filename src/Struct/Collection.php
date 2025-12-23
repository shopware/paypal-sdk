<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct;

/**
 * @template TElement of Struct = Struct
 *
 * @implements \IteratorAggregate<array-key, TElement>
 * @implements \ArrayAccess<array-key, TElement>
 */
abstract class Collection implements \IteratorAggregate, \Countable, \JsonSerializable, \ArrayAccess
{
    /** @var array<array-key, TElement> */
    protected array $elements = [];

    /**
     * @param iterable<array-key, TElement> $elements
     */
    final public function __construct(iterable $elements = [])
    {
        foreach ($elements as $key => $element) {
            $this->set($key, $element);
        }
    }

    /**
     * @param TElement $element
     */
    public function set(string|int $key, Struct $element): void
    {
        $this->validateType($element);

        $this->elements[$key] = $element;
    }

    /**
     * @return class-string<TElement>
     */
    abstract public static function getExpectedClass(): string;

    /**
     * @param array<mixed> $associativeData
     */
    public static function createFromAssociative(array $associativeData): static
    {
        $collection = new static();

        foreach (\array_filter($associativeData) as $value) {
            if ($value instanceof Struct) {
                $collection->add($value);
            } elseif (\is_array($value)) {
                $collection->add(Struct::from(static::getExpectedClass(), $value));
            }
        }

        return $collection;
    }

    /**
     * @param TElement $element
     */
    public function add(Struct $element): void
    {
        $this->validateType($element);

        $this->elements[] = $element;
    }

    /**
     * @return TElement|null
     */
    public function get(string|int $key): ?Struct
    {
        if ($this->has($key)) {
            return $this->elements[$key];
        }

        return null;
    }

    public function has(string|int $key): bool
    {
        return \array_key_exists($key, $this->elements);
    }

    public function clear(): void
    {
        $this->elements = [];
    }

    public function count(): int
    {
        return \count($this->elements);
    }

    /**
     * @return list<array-key>
     */
    public function getKeys(): array
    {
        return \array_keys($this->elements);
    }

    /**
     * @template T
     * @template I
     *
     * @param \Closure(I|T, TElement): T $closure
     * @param I $initial
     *
     * @return T|I
     */
    public function reduce(\Closure $closure, mixed $initial = null): mixed
    {
        return \array_reduce($this->elements, $closure, $initial);
    }

    /**
     * @template T
     *
     * @param \Closure(TElement): T $closure
     *
     * @return array<array-key, T>
     */
    public function fmap(\Closure $closure): array
    {
        return \array_filter($this->map($closure));
    }

    /**
     * @template T
     *
     * @param \Closure(TElement): T $closure
     *
     * @return array<array-key, T>
     */
    public function map(\Closure $closure): array
    {
        return \array_map($closure, $this->elements);
    }

    /**
     * @param \Closure(TElement, TElement): int $closure
     */
    public function sort(\Closure $closure): void
    {
        \uasort($this->elements, $closure);
    }

    /**
     * @param \Closure(TElement): bool $closure
     */
    public function filter(\Closure $closure): static
    {
        return $this->createNew(\array_filter($this->elements, $closure));
    }

    public function slice(int $offset, ?int $length = null): static
    {
        return $this->createNew(\array_slice($this->elements, $offset, $length, true));
    }

    /**
     * @return array<array-key, TElement>
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    /**
     * @return list<array>
     */
    public function jsonSerialize(): array
    {
        return \array_values($this->map(static fn (Struct $e): array => $e->jsonSerialize()));
    }

    /**
     * @return TElement|null
     */
    public function first(): ?Struct
    {
        return \array_values($this->elements)[0] ?? null;
    }

    /**
     * @return TElement|null
     */
    public function getAt(int $position): ?Struct
    {
        return \array_values($this->elements)[$position] ?? null;
    }

    /**
     * @return TElement|null
     */
    public function last(): ?Struct
    {
        return \array_values($this->elements)[\count($this->elements) - 1] ?? null;
    }

    public function remove(string|int $key): void
    {
        unset($this->elements[$key]);
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->elements);
    }

    public function offsetExists(mixed $offset): bool
    {
        return $this->has($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset !== null) {
            $this->set($offset, $value);
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->remove($offset);
    }

    /**
     * @param TElement $element
     */
    protected function validateType(Struct $element): void
    {
        $expectedClass = static::getExpectedClass();

        if (!$element instanceof $expectedClass) {
            throw new \InvalidArgumentException(
                \sprintf('Expected collection element of type %s got %s', $expectedClass, $element::class)
            );
        }
    }

    /**
     * @param iterable<array-key, TElement> $elements
     */
    protected function createNew(iterable $elements = []): static
    {
        return new static($elements);
    }
}
