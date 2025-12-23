<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct;

use Shopware\PayPalSDK\Util\CaseConverter;

abstract class Struct implements \JsonSerializable
{
    final public function __construct() {}

    /**
     * @template T of Struct
     *
     * @param class-string<T> $class
     * @param array<mixed> $data
     *
     * @return T
     */
    final public static function from(string $class, array $data): Struct
    {
        if (!\class_exists($class) || !\is_a($class, Struct::class, true)) {
            throw new \InvalidArgumentException('Class ' . $class . ' is not type of ' . self::class);
        }

        return (new $class())->assign($data);
    }

    /**
     * @param array<mixed> $data
     */
    public function assign(array $data): static
    {
        foreach ($data as $key => $value) {
            $propertyName = CaseConverter::denormalize((string) $key);

            if ($value === null || $value === []) {
                continue;
            }

            if (!\property_exists($this, $propertyName)) {
                continue;
            }

            $property = new \ReflectionProperty($this, $propertyName);

            if (!$type = $property->getType()) {
                $this->assignValue($propertyName, $value);

                continue;
            }

            if (\is_array($value) && $className = $this->getPropertyClassType([$type], Collection::class)) {
                $this->assignValue($propertyName, $className::createFromAssociative($value));

                continue;
            }

            if (\is_array($value) && $className = $this->getPropertyClassType([$type], Struct::class)) {
                $this->assignValue($propertyName, self::from($className, $value));

                continue;
            }

            $this->assignValue($propertyName, $value);
        }

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $data = [];

        foreach (\array_keys(\get_class_vars($this::class)) as $property) {
            $snakeCasePropertyName = CaseConverter::normalize($property);

            if ((new \ReflectionProperty($this, $property))->isInitialized($this)) {
                $data[$snakeCasePropertyName] = $this->{$property};
            }
        }

        return $data;
    }

    public function unset(string $propertyName): void
    {
        unset($this->{$propertyName});
    }

    public function isset(string $propertyName): bool
    {
        return isset($this->{$propertyName});
    }

    /**
     * @template T
     *
     * @param \ReflectionType[] $types
     * @param class-string<T> $expectedClass
     *
     * @return (class-string&T)|class-string<T>|null
     */
    private function getPropertyClassType(array $types, string $expectedClass): ?string
    {
        foreach ($types as $type) {
            $type = match (true) {
                $type instanceof \ReflectionNamedType => $type,
                $type instanceof \ReflectionUnionType => $this->getPropertyClassType($type->getTypes(), $expectedClass),
                $type instanceof \ReflectionIntersectionType => $this->getPropertyClassType($type->getTypes(), $expectedClass),
                default => null,
            };

            if (!$type instanceof \ReflectionNamedType || $type->isBuiltin()) {
                return null;
            }

            $name = $type->getName();

            if (\class_exists($name) && \is_a($name, $expectedClass, true)) {
                return $name;
            }
        }

        return null;
    }

    private function assignValue(string $propertyName, mixed $value): void
    {
        $setterMethod = \sprintf('set%s', \ucfirst($propertyName));

        if (\method_exists($this, $setterMethod)) {
            $this->{$setterMethod}($value);
        } else {
            $this->{$propertyName} = $value;
        }
    }
}
