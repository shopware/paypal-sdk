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
    /**
     * Per class and input key: property name, setter, nested struct or collection class and whether
     * the property accepts arrays. Only the property name and its snake_case form are cached, so external
     * input cannot grow it with unknown keys or spellings like `purchase__units`.
     *
     * @var array<class-string<self>, array<array-key, array{string, ?string, class-string<self>|class-string<Collection>|null, bool}>>
     */
    private static array $assignMetadata = [];

    /** @var array<string, string> */
    private static array $jsonKeys = [];

    final public function __construct() {}

    /**
     * @param array<mixed> $data
     *
     * @throws \LogicException see {@see self::assign()}
     */
    final public static function from(#[\SensitiveParameter] array $data): static
    {
        // @phpstan-ignore-next-line new.staticInAbstractClassStaticMethod assume class is not abstract anymore
        return (new static())->assign($data);
    }

    /**
     * Null values keep the current value. Arrays that fit neither a nested struct, a collection
     * nor an array property are skipped.
     *
     * @param array<mixed> $data
     *
     * @throws \LogicException for a property typed as an abstract struct, or as a union with a struct or collection
     */
    public function assign(#[\SensitiveParameter] array $data): static
    {
        foreach ($data as $key => $value) {
            if ($value === null) {
                continue;
            }

            $metadata = self::$assignMetadata[$this::class][$key] ?? self::describe($this::class, $key);
            if ($metadata === null) {
                continue;
            }

            [$name, $setter, $target, $acceptsArray] = $metadata;

            if (\is_array($value)) {
                $value = match (true) {
                    $target === null => $acceptsArray ? $value : null,
                    $value === [] && !\is_a($target, Collection::class, true) => null,
                    default => $target::from($value),
                };

                if ($value === null) {
                    continue;
                }
            }

            if ($setter !== null) {
                $this->{$setter}($value);
            } else {
                $this->{$name} = $value;
            }
        }

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $data = [];

        foreach (\get_object_vars($this) as $property => $value) {
            // collection getters create empty collections on read, which must not change the output
            if ($value === null || ($value instanceof Collection && $value->count() === 0)) {
                continue;
            }

            if ($value instanceof \DateTimeInterface) {
                $value = $value->format(\DateTimeInterface::ATOM);
            } elseif ($value instanceof \JsonSerializable) {
                $value = $value->jsonSerialize();
            }

            $data[self::$jsonKeys[$property] ??= CaseConverter::normalize($property)] = $value;
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
     * Returns all initialized class properties mapped to their value.
     * Unlike {@see self::jsonSerialize()}, property names are not normalized
     * and properties return as actual values and struct instances.
     *
     * @return array<string, mixed>
     */
    public function getVars(): array
    {
        return \get_object_vars($this);
    }

    /**
     * @param class-string<self> $class
     *
     * @throws \LogicException
     *
     * @return array{string, ?string, class-string<self>|class-string<Collection>|null, bool}|null
     */
    private static function describe(string $class, int|string $key): ?array
    {
        $name = CaseConverter::denormalize((string) $key);
        if (!\property_exists($class, $name)) {
            return null;
        }

        $type = (new \ReflectionProperty($class, $name))->getType();
        $members = $type instanceof \ReflectionUnionType ? $type->getTypes() : [$type];
        $target = null;
        $acceptsArray = $type === null;

        foreach ($members as $member) {
            if (!$member instanceof \ReflectionNamedType) {
                continue;
            }

            $typeName = $member->getName();

            if ($member->isBuiltin()) {
                $acceptsArray = $acceptsArray || \in_array($typeName, ['array', 'iterable', 'mixed'], true);

                continue;
            }

            if (!\is_a($typeName, self::class, true) && !\is_a($typeName, Collection::class, true)) {
                continue;
            }

            if (\count($members) > 1 || !(new \ReflectionClass($typeName))->isInstantiable()) {
                throw new \LogicException(\sprintf('Unable to resolve a concrete class for %s::$%s, override assign() to hydrate it', $class, $name));
            }

            $target = $typeName;
        }

        $setter = 'set' . \ucfirst($name);

        $metadata = [$name, \method_exists($class, $setter) ? $setter : null, $target, $acceptsArray];
        if ($key === $name || $key === CaseConverter::normalize($name)) {
            self::$assignMetadata[$class][$key] = $metadata;
        }

        return $metadata;
    }
}
