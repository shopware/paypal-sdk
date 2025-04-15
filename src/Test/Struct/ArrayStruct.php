<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Test\Struct;

use Shopware\PayPalSDK\Struct\Struct;

/**
 * A struct allowing for unstructured data.
 *
 * @implements \IteratorAggregate<array-key, mixed>
 * @implements \ArrayAccess<array-key, mixed>
 */
class ArrayStruct extends Struct implements \ArrayAccess, \IteratorAggregate, \Countable
{
    /** @var array<mixed> */
    protected array $data = [];

    public function assign(array $data): static
    {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }

    /**
     * @return array<array-key, mixed>
     */
    public function jsonSerialize(): array
    {
        $data = [];

        foreach ($this->data as $key => $value) {
            $data[$key] = match (true) {
                $value instanceof \JsonSerializable => $value->jsonSerialize(),
                default => $value,
            };
        }

        return $data;
    }

    /**
     * @template S of Struct
     *
     * @param class-string<S> $struct
     */
    public function into(string $struct): Struct
    {
        return self::from($struct, $this->jsonSerialize());
    }

    public function has(string|int $key): bool
    {
        return isset($this->data[$key]);
    }

    public function get(string|int $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    public function getStruct(string|int $key): ?self
    {
        $value = $this->get($key);

        return $value instanceof self ? $value : null;
    }

    public function set(string|int $key, mixed $value): self
    {
        $this->data[$key] = \is_array($value) ? (new self())->assign($value) : $value;

        return $this;
    }

    public function remove(string|int ...$keys): self
    {
        foreach ($keys as $key) {
            unset($this->data[$key]);
        }

        return $this;
    }

    /**
     * @return list<array-key>
     */
    public function getKeys(): array
    {
        return \array_keys($this->data);
    }

    /**
     * Will return the inner data, will contain other ArrayStruct.
     * To get a pure array use {@see self::jsonSerialize()}.
     *
     * @return array<mixed>
     */
    public function getData(): array
    {
        return $this->data;
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

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->data);
    }

    public function count(): int
    {
        return \count($this->data);
    }
}
