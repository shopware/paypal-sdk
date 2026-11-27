<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tools\OpenApi;

use OpenApi\Attributes\Property;
use Shopware\PayPalSDK\Struct\Collection;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Util\CaseConverter;

/**
 * Checks the structs against PayPal's OpenAPI specs.
 *
 * From each {@see Operation}, structs and schemas are walked in parallel along
 * the property names. A struct reached through several schemas is held to
 * their union: a field counts as modelled if any of them defines it, an enum
 * value if any of them lists it.
 */
final class StructConformance
{
    /** @var array<class-string<Struct>, list<Pairing>> */
    private array $pairings = [];

    /** @var array<string, true> */
    private array $visited = [];

    /**
     * Full response examples that omit a property but contain its object.
     *
     * @var array<class-string<Struct>, array<string, list<string>>> class => property => examples omitting it
     */
    private array $absentIn = [];

    /**
     * @param list<Operation> $operations
     * @param array<string, string> $uncovered see {@see Roots::UNCOVERED}
     * @param list<class-string<Struct>> $structs the concrete structs that must be paired or uncovered
     */
    public function __construct(
        private readonly SpecRepository $specs,
        private readonly array $operations,
        private readonly array $uncovered,
        private readonly array $structs,
    ) {}

    /**
     * @return list<class-string<Struct>>
     */
    public static function findStructs(string $dir, string $namespace): array
    {
        $structs = [];
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)) as $file) {
            if (!$file instanceof \SplFileInfo || $file->getExtension() !== 'php') {
                continue;
            }

            $relative = substr($file->getPathname(), \strlen($dir) + 1, -4);
            $class = $namespace . '\\' . str_replace('/', '\\', $relative);
            if (class_exists($class) && is_subclass_of($class, Struct::class) && !(new \ReflectionClass($class))->isAbstract()) {
                $structs[] = $class;
            }
        }
        sort($structs);

        return $structs;
    }

    public function run(): Report
    {
        $this->pairings = [];
        $this->visited = [];
        $this->absentIn = [];

        foreach ($this->operations as $operation) {
            $this->pairOperation($operation);
        }

        $findings = [];
        foreach ($this->pairings as $class => $pairings) {
            array_push($findings, ...$this->analyse($class, $pairings));
        }
        array_push($findings, ...$this->compareAttributes());

        $reached = $this->reachable();
        $coveredBy = [];
        foreach ($this->structs as $class) {
            $entry = isset($reached[$class]) ? null : $this->uncoveredEntry($class);
            if ($entry !== null) {
                $coveredBy[$entry] = true;
            } elseif (!isset($reached[$class])) {
                $findings[] = new Finding(Finding::UNPAIRED, $class, '', 'no operation in Roots reaches it, and Roots::UNCOVERED does not list it');
            }
        }

        $staleUncovered = array_values(array_diff(array_keys($this->uncovered), array_keys($coveredBy)));

        return new Report($findings, $this->pairings, \count($this->structs), $staleUncovered);
    }

    private function pairOperation(Operation $operation): void
    {
        $spec = $this->specs->get($operation->spec);
        $endpoint = $spec->operation($operation->method, $operation->path)
            ?? throw new \RuntimeException($operation . ' is not in the spec');

        if ($operation->sends !== null) {
            $body = $endpoint['request'] ?? throw new \RuntimeException($operation . ' takes no request body');
            $this->pairBody($operation->sends, $spec, $body, $operation->path . ' request', true);
        }

        if ($operation->receives === null) {
            return;
        }

        if ($endpoint['responses'] === []) {
            throw new \RuntimeException($operation . ' has no 2xx response body');
        }

        foreach ($endpoint['responses'] as $body) {
            $this->pairBody($operation->receives, $spec, $body, $operation->path . ' response', false);
        }

        foreach ($endpoint['examples'] as $name => $example) {
            if (!self::isMinimal($example)) {
                $this->observe($operation->receives, $example, $operation . ' ' . $name);
            }
        }
    }

    /**
     * @param class-string<Struct> $class
     * @param array<mixed> $schema
     */
    private function pairBody(string $class, Spec $spec, array $schema, string $inlineLabel, bool $isRequest): void
    {
        // a JSON array body pairs its elements
        $flat = $spec->flatten($schema);
        if (($flat['type'] ?? null) === 'array' && \is_array($flat['items'] ?? null)) {
            $schema = $flat['items'];
            $inlineLabel .= '[]';
        }

        $this->pair($class, $spec, $spec->schemaName($schema) ?? $inlineLabel, $schema, $isRequest);
    }

    /**
     * @param class-string<Struct> $class
     * @param array<mixed> $schema
     */
    private function pair(string $class, Spec $spec, string $label, array $schema, bool $isRequest): void
    {
        $key = implode('|', [$class, $spec->name, $label, $isRequest ? 'request' : 'response']);
        if (isset($this->visited[$key])) {
            return;
        }
        $this->visited[$key] = true;

        $pairing = new Pairing($spec, $label, $isRequest, $spec->flatten($schema));
        $this->pairings[$class][] = $pairing;

        foreach (self::properties($class) as $property) {
            $target = self::structTarget($property);
            $fieldSchema = $pairing->fieldSchema($property->getName());
            if ($target === null || $fieldSchema === null) {
                continue;
            }

            $inlineLabel = $label . '.' . $pairing->field($property->getName());
            if (self::sdkKind($property) === 'collection') {
                $items = $spec->flatten($fieldSchema)['items'] ?? null;
                if (!\is_array($items)) {
                    continue;
                }
                $fieldSchema = $items;
                $inlineLabel .= '[]';
            }

            // compareType() reports a struct standing in for a scalar
            if (self::specKind($spec->flatten($fieldSchema)) !== 'object') {
                continue;
            }

            $this->pair($target, $spec, $spec->schemaName($fieldSchema) ?? $inlineLabel, $fieldSchema, $isRequest);
        }
    }

    /**
     * A `Prefer: return=minimal` body, holding only the identifying fields.
     *
     * @param array<mixed> $example
     */
    private static function isMinimal(array $example): bool
    {
        return array_diff(array_keys($example), ['id', 'status', 'links']) === [];
    }

    /**
     * Records which properties a response example omits, along the struct tree.
     *
     * @param class-string<Struct> $class
     * @param array<mixed> $data
     */
    private function observe(string $class, array $data, string $example): void
    {
        $values = [];
        foreach ($data as $field => $value) {
            $values[CaseConverter::denormalize((string) $field)] = $value;
        }

        foreach (self::properties($class) as $property) {
            $name = $property->getName();
            $value = $values[$name] ?? null;

            $this->absentIn[$class][$name] ??= [];
            if ($value === null) {
                $this->absentIn[$class][$name][] = $example;

                continue;
            }

            $target = self::structTarget($property);
            if ($target === null || !\is_array($value)) {
                continue;
            }

            foreach (self::sdkKind($property) === 'collection' ? $value : [$value] as $element) {
                if (\is_array($element)) {
                    $this->observe($target, $element, $example);
                }
            }
        }
    }

    /**
     * @param class-string<Struct> $class
     * @param list<Pairing> $pairings
     *
     * @return list<Finding>
     */
    private function analyse(string $class, array $pairings): array
    {
        $findings = [];
        $reflection = new \ReflectionClass($class);
        $isReceived = array_filter($pairings, static fn (Pairing $p): bool => !$p->isRequest) !== [];
        $modelled = [];

        foreach (self::properties($class) as $property) {
            $name = $property->getName();
            $modelled[$name] = true;

            $optionalIn = array_filter($pairings, static fn (Pairing $p): bool => !$p->isRequired($name));
            if (!$isReceived && $optionalIn !== [] && !($property->getType()?->allowsNull() ?? true)) {
                $findings[] = new Finding(Finding::OPTIONAL, $class, $name, 'non-nullable, but optional in ' . self::list(array_values($optionalIn)));
            }

            $defining = array_values(array_filter($pairings, static fn (Pairing $p): bool => $p->fieldSchema($name) !== null));
            if ($defining === []) {
                $findings[] = self::missingField($class, $name, $pairings);

                continue;
            }

            foreach ($defining as $pairing) {
                $message = self::compareType($property, (array) $pairing->flatFieldSchema($name));
                if ($message !== null) {
                    $findings[] = new Finding(Finding::TYPE, $class, $name, $message . ' (' . $pairing . ')');
                }
            }

            array_push($findings, ...self::compareEnum($class, $property, $defining));

            $serialized = self::serializedField($reflection, $property);
            foreach ($defining as $pairing) {
                $field = $pairing->field($name);
                if ($pairing->isRequest && $serialized !== null && $serialized !== $field) {
                    $findings[] = new Finding(Finding::FIELD, $class, $name, \sprintf('jsonSerialize() writes "%s", spec field "%s" (%s)', $serialized, $field, $pairing));
                }
            }

            $absentIn = $this->absentIn[$class][$name] ?? [];
            if ($isReceived && $absentIn !== [] && self::getterThrowsWhenAbsent($reflection, $property)) {
                $findings[] = new Finding(Finding::GETTER, $class, $name, \sprintf(
                    'getter throws when absent, and %d full response example(s) omit the field, e.g. %s',
                    \count($absentIn),
                    $absentIn[0],
                ));
            }
        }

        $missing = [];
        foreach ($pairings as $pairing) {
            foreach ($pairing->fields() as $field) {
                if (!isset($modelled[CaseConverter::denormalize($field)])) {
                    $missing[$field][] = $pairing;
                }
            }
        }

        foreach ($missing as $field => $definedBy) {
            $findings[] = new Finding(Finding::SPEC_ONLY, $class, CaseConverter::denormalize((string) $field), 'defined by ' . self::list($definedBy));
        }

        return $findings;
    }

    /**
     * A field that differs from the property only in case and underscores,
     * e.g. `mybank` for `$myBank`, is a naming bug rather than a field the
     * spec lacks.
     *
     * @param class-string<Struct> $class
     * @param list<Pairing> $pairings
     */
    private static function missingField(string $class, string $property, array $pairings): Finding
    {
        $similar = [];
        foreach ($pairings as $pairing) {
            foreach ($pairing->fields() as $field) {
                if (strtolower(str_replace('_', '', $field)) === strtolower($property)) {
                    $similar[$field][] = $pairing;
                }
            }
        }

        $field = array_key_first($similar);
        if ($field === null) {
            return new Finding(Finding::SDK_ONLY, $class, $property, 'not defined by ' . self::list($pairings));
        }

        return new Finding(Finding::FIELD, $class, $property, \sprintf('neither read from nor written to spec field "%s" (%s)', $field, self::list($similar[$field])));
    }

    /**
     * Facets are compared on the class that declares the property, so a
     * property inherited by several structs gets the facets of all their
     * schemas.
     *
     * @return list<Finding>
     */
    private function compareAttributes(): array
    {
        /** @var array<class-string, array<string, array{request: list<array<string, int|float|string>>, response: list<array<string, int|float|string>>}>> $sets */
        $sets = [];
        foreach ($this->pairings as $class => $pairings) {
            foreach (self::properties($class) as $property) {
                foreach ($pairings as $pairing) {
                    $schema = $pairing->flatFieldSchema($property->getName());
                    if ($schema !== null) {
                        $sets[$property->class][$property->getName()] ??= ['request' => [], 'response' => []];
                        $sets[$property->class][$property->getName()][$pairing->isRequest ? 'request' : 'response'][] = Facets::fromSchema($schema, self::sdkKind($property));
                    }
                }
            }
        }

        $findings = [];
        foreach ($sets as $class => $properties) {
            foreach ($properties as $name => $byDirection) {
                $property = new \ReflectionProperty($class, $name);
                if ($property->getAttributes(Property::class) === []) {
                    continue;
                }

                // what may be sent is the contract
                $expected = Facets::merge($byDirection['request'] !== [] ? $byDirection['request'] : $byDirection['response']);
                foreach (Facets::diff(Facets::fromAttribute($property), $expected) as $facet => $change) {
                    $findings[] = new Finding(Finding::ATTRIBUTE, $class, $name, $change, $change, [$facet => $expected[$facet] ?? null]);
                }
            }
        }

        return $findings;
    }

    /**
     * The {@see Roots::UNCOVERED} entry listing the struct or its namespace.
     */
    private function uncoveredEntry(string $class): ?string
    {
        $short = Finding::shortClass($class);
        foreach (array_keys($this->uncovered) as $entry) {
            if ($short === $entry || str_starts_with($short, $entry . '\\')) {
                return $entry;
            }
        }

        return null;
    }

    /**
     * The structs the operations send or receive, and those they nest,
     * regardless of the spec.
     *
     * @return array<class-string<Struct>, true>
     */
    private function reachable(): array
    {
        $queue = [];
        foreach ($this->operations as $operation) {
            $queue[] = $operation->sends;
            $queue[] = $operation->receives;
        }

        $reached = [];
        while ($queue !== []) {
            $class = array_pop($queue);
            if ($class === null || isset($reached[$class])) {
                continue;
            }

            $reached[$class] = true;
            foreach (self::properties($class) as $property) {
                $queue[] = self::structTarget($property);
            }
        }

        return $reached;
    }

    /**
     * @param array<mixed> $schema
     */
    private static function compareType(\ReflectionProperty $property, array $schema): ?string
    {
        $sdk = self::sdkKind($property);
        $spec = self::specKind($schema);
        if ($sdk === null || $spec === null) {
            return null;
        }

        /** @var array<mixed> $items */
        $items = \is_array($schema['items'] ?? null) ? $schema['items'] : [];
        $mismatch = \sprintf('SDK %s, spec %s', self::describeSdk($property), self::describeSpec($schema, $spec));

        $compatible = match ($sdk) {
            'string', 'datetime' => $spec === 'string',
            'int' => $spec === 'int',
            'float' => $spec === 'float' || $spec === 'int',
            'bool' => $spec === 'bool',
            // an untyped array loses the structure of an object
            'array' => $spec === 'map' || ($spec === 'array' && self::specKind($items) !== 'object'),
            // a free-form object would lose the keys the struct does not model
            'struct' => $spec === 'object',
            'collection' => $spec === 'array' && self::specKind($items) === 'object',
            default => true,
        };

        if (!$compatible) {
            // a casting setter, e.g. `setQuantity(int|string)`
            return self::setterAccepts($property, $spec) ? null : $mismatch;
        }

        $target = self::structTarget($property);
        $object = $sdk === 'collection' ? $items : $schema;
        if ($target === null || self::specKind($object) !== 'object' || self::properties($target) === []) {
            return null;
        }

        $fields = array_map(
            static fn (int|string $field): string => CaseConverter::denormalize((string) $field),
            array_keys(\is_array($object['properties'] ?? null) ? $object['properties'] : []),
        );
        $names = array_map(static fn (\ReflectionProperty $p): string => $p->getName(), self::properties($target));

        return array_intersect($names, $fields) === [] ? $mismatch . ', no field in common' : null;
    }

    /**
     * @param class-string<Struct> $class
     * @param list<Pairing> $pairings the pairings defining the property
     *
     * @return list<Finding>
     */
    private static function compareEnum(string $class, \ReflectionProperty $property, array $pairings): array
    {
        $sdkEnum = ($property->getAttributes(Property::class)[0] ?? null)?->getArguments()['enum'] ?? null;
        if (!\is_array($sdkEnum)) {
            return [];
        }

        $toString = static fn (mixed $value): string => \is_scalar($value) ? (string) $value : '';
        $specEnum = [];
        foreach ($pairings as $pairing) {
            $enum = $pairing->flatFieldSchema($property->getName())['enum'] ?? null;
            foreach (\is_array($enum) ? $enum : [] as $value) {
                $specEnum[$toString($value)][] = $pairing;
            }
        }

        if ($specEnum === []) {
            return [];
        }

        $sdkEnum = array_map($toString, $sdkEnum);
        $findings = [];
        foreach (array_diff($sdkEnum, array_keys($specEnum)) as $value) {
            $findings[] = new Finding(Finding::ENUM, $class, $property->getName(), \sprintf('SDK value %s is in no spec enum', $value), '-' . $value);
        }
        foreach (array_diff(array_keys($specEnum), $sdkEnum) as $value) {
            $findings[] = new Finding(Finding::ENUM, $class, $property->getName(), \sprintf('spec value %s is missing (%s)', $value, self::list($specEnum[$value])), '+' . $value);
        }

        return $findings;
    }

    /**
     * The field `jsonSerialize()` writes the property to, when set alone.
     *
     * @param \ReflectionClass<Struct> $class
     */
    private static function serializedField(\ReflectionClass $class, \ReflectionProperty $property): ?string
    {
        $value = self::sampleValue($property);
        if ($value === null || $class->isAbstract()) {
            return null;
        }

        $instance = $class->newInstance();
        $instance->unset($property->getName());

        try {
            $without = array_keys($instance->jsonSerialize());
            $property->setValue($instance, $value);
            $added = array_values(array_diff(array_keys($instance->jsonSerialize()), $without));
        } catch (\Throwable) {
            return null;
        }

        return \count($added) === 1 ? (string) $added[0] : null;
    }

    private static function sampleValue(\ReflectionProperty $property): mixed
    {
        $type = $property->getType();
        if (!$type instanceof \ReflectionNamedType) {
            return null;
        }

        $name = $type->getName();

        return match (true) {
            $name === 'string' => 'x',
            $name === 'int' => 1,
            $name === 'float' => 1.5,
            $name === 'bool' => true,
            $name === 'array' => ['x'],
            is_a($name, Collection::class, true), is_a($name, Struct::class, true) => (new \ReflectionClass($name))->isAbstract() ? null : new $name(),
            is_a(\DateTimeImmutable::class, $name, true) => new \DateTimeImmutable(),
            default => null,
        };
    }

    /**
     * Whether the getter throws on a fresh instance.
     *
     * @param \ReflectionClass<Struct> $class
     */
    private static function getterThrowsWhenAbsent(\ReflectionClass $class, \ReflectionProperty $property): bool
    {
        if ($class->isAbstract()) {
            return false;
        }

        $name = ucfirst($property->getName());
        foreach (['get' . $name, 'is' . $name, $property->getName()] as $candidate) {
            if (!$class->hasMethod($candidate)) {
                continue;
            }

            $getter = $class->getMethod($candidate);
            if (!$getter->isPublic() || $getter->getNumberOfRequiredParameters() > 0) {
                continue;
            }

            try {
                $getter->invoke($class->newInstance());
            } catch (\Error) {
                return true;
            }

            return false;
        }

        return false;
    }

    /**
     * @param class-string<Struct> $class
     *
     * @return list<\ReflectionProperty>
     */
    private static function properties(string $class): array
    {
        return array_values(array_filter(
            (new \ReflectionClass($class))->getProperties(),
            static fn (\ReflectionProperty $p): bool => !$p->isStatic(),
        ));
    }

    /**
     * The struct a property nests, or the element struct of its collection.
     *
     * @return class-string<Struct>|null
     */
    private static function structTarget(\ReflectionProperty $property): ?string
    {
        $type = $property->getType();
        if (!$type instanceof \ReflectionNamedType || $type->isBuiltin()) {
            return null;
        }

        $name = $type->getName();

        return match (true) {
            is_a($name, Collection::class, true) => $name::getExpectedClass(),
            is_a($name, Struct::class, true) => $name,
            default => null,
        };
    }

    /**
     * Whether the setter coerces the spec's scalar type into the property's.
     */
    private static function setterAccepts(\ReflectionProperty $property, string $specKind): bool
    {
        $class = $property->getDeclaringClass();
        $setter = 'set' . ucfirst($property->getName());
        if (!$class->hasMethod($setter) || !\in_array($specKind, ['string', 'int', 'float', 'bool'], true)) {
            return false;
        }

        $parameters = $class->getMethod($setter)->getParameters();
        $type = $parameters === [] ? null : $parameters[0]->getType();
        $accepted = match (true) {
            $type instanceof \ReflectionNamedType => [$type->getName()],
            $type instanceof \ReflectionUnionType => array_map(
                static fn (\ReflectionType $t): string => $t instanceof \ReflectionNamedType ? $t->getName() : '',
                $type->getTypes(),
            ),
            default => [],
        };

        return \in_array($specKind, $accepted, true) || \in_array('mixed', $accepted, true);
    }

    private static function sdkKind(\ReflectionProperty $property): ?string
    {
        $type = $property->getType();
        if (!$type instanceof \ReflectionNamedType) {
            return null;
        }

        $name = $type->getName();

        return match (true) {
            \in_array($name, ['string', 'int', 'float', 'bool', 'array'], true) => $name,
            is_a($name, Collection::class, true) => 'collection',
            is_a($name, Struct::class, true) => 'struct',
            is_a($name, \DateTimeInterface::class, true) => 'datetime',
            default => null,
        };
    }

    /**
     * @param array<mixed> $schema flattened
     */
    private static function specKind(array $schema): ?string
    {
        $type = $schema['type'] ?? null;
        if (\is_array($type)) {
            $type = array_values(array_diff($type, ['null']))[0] ?? null;
        }

        if ($type === 'object' || isset($schema['properties'])) {
            return \is_array($schema['properties'] ?? null) && $schema['properties'] !== [] ? 'object' : 'map';
        }

        return match ($type) {
            'array' => 'array',
            'string' => 'string',
            'integer' => 'int',
            'number' => 'float',
            'boolean' => 'bool',
            default => isset($schema['enum']) ? 'string' : null,
        };
    }

    private static function describeSdk(\ReflectionProperty $property): string
    {
        $type = $property->getType();
        $name = $type instanceof \ReflectionNamedType ? $type->getName() : (string) $type;

        return class_exists($name) ? Finding::shortClass($name) : $name;
    }

    /**
     * @param array<mixed> $schema
     */
    private static function describeSpec(array $schema, string $kind): string
    {
        $title = \is_string($schema['title'] ?? null) ? ' "' . $schema['title'] . '"' : '';

        return match ($kind) {
            'object' => 'object' . $title,
            'map' => 'object without properties',
            'array' => 'array of ' . (self::specKind(\is_array($schema['items'] ?? null) ? $schema['items'] : []) ?? 'unknown'),
            default => $kind . (\is_string($schema['format'] ?? null) ? ' (' . $schema['format'] . ')' : ''),
        };
    }

    /**
     * @param list<Pairing> $pairings
     */
    private static function list(array $pairings): string
    {
        return implode(', ', array_unique(array_map(strval(...), $pairings)));
    }
}
