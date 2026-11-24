<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tools\OpenApi;

use OpenApi\Attributes\Property;

/**
 * The `#[OA\Property]` arguments taken from the spec. All other arguments are
 * owned by the SDK.
 */
final class Facets
{
    /**
     * In the order they are written.
     */
    public const array MANAGED = ['format', 'minLength', 'maxLength', 'pattern', 'minimum', 'maximum', 'minItems', 'maxItems'];

    private const array LOWER_BOUNDS = ['minLength', 'minimum', 'minItems'];

    private const array UPPER_BOUNDS = ['maxLength', 'maximum', 'maxItems'];

    /**
     * PayPal's "unbounded" sentinels for lengths and item counts.
     */
    private const int UNBOUNDED_LENGTH = 2147483647;

    private const int UNBOUNDED_ITEMS = 32767;

    /**
     * Patterns that match every string.
     */
    private const array ANY_STRING = ['^[\S\s]*$', '^[\s\S]*$', '^.*$', '.*'];

    /**
     * The facets a schema puts on a property of the given SDK kind.
     *
     * @param array<mixed> $schema flattened property schema
     *
     * @return array<string, int|float|string>
     */
    public static function fromSchema(array $schema, ?string $sdkKind): array
    {
        $applicable = match ($sdkKind) {
            'string' => ['format', 'minLength', 'maxLength', 'pattern'],
            'int', 'float' => ['minimum', 'maximum'],
            'array', 'collection' => ['minItems', 'maxItems'],
            default => [],
        };

        $facets = [];
        foreach ($applicable as $facet) {
            $value = $facet === 'format' ? self::format($schema['format'] ?? null) : ($schema[$facet] ?? null);

            if (\is_string($value) || \is_int($value) || \is_float($value)) {
                $facets[$facet] = $value;
            }
        }

        // drop facets that constrain nothing
        if (($facets['minLength'] ?? null) === 0) {
            unset($facets['minLength']);
        }
        if (($facets['minItems'] ?? null) === 0) {
            unset($facets['minItems']);
        }
        if (($facets['maxLength'] ?? 0) >= self::UNBOUNDED_LENGTH) {
            unset($facets['maxLength']);
        }
        if (($facets['maxItems'] ?? 0) >= self::UNBOUNDED_ITEMS) {
            unset($facets['maxItems']);
        }
        if (\in_array($facets['pattern'] ?? null, self::ANY_STRING, true)) {
            unset($facets['pattern']);
        }

        return $facets;
    }

    /**
     * The loosest facets that satisfy every given set.
     *
     * @param list<array<string, int|float|string>> $sets
     *
     * @return array<string, int|float|string>
     */
    public static function merge(array $sets): array
    {
        if ($sets === []) {
            return [];
        }

        $merged = [];
        foreach (self::MANAGED as $facet) {
            $values = [];
            foreach ($sets as $set) {
                if (!\array_key_exists($facet, $set)) {
                    continue 2;
                }
                $values[] = $set[$facet];
            }

            $value = match (true) {
                \in_array($facet, self::LOWER_BOUNDS, true) => min($values),
                \in_array($facet, self::UPPER_BOUNDS, true) => max($values),
                \count(array_unique($values)) === 1 => $values[0],
                default => null,
            };

            if ($value !== null) {
                $merged[$facet] = $value;
            }
        }

        return $merged;
    }

    /**
     * The managed facets an attribute currently declares.
     *
     * @return array<string, mixed>
     */
    public static function fromAttribute(\ReflectionProperty $property): array
    {
        $attribute = $property->getAttributes(Property::class)[0] ?? null;
        if ($attribute === null) {
            return [];
        }

        return array_intersect_key($attribute->getArguments(), array_flip(self::MANAGED));
    }

    /**
     * The facets to change, as facet => change, e.g. `maxLength 120 -> 127`,
     * `+pattern "^[A-Z]+$"` or `-minLength`.
     *
     * @param array<string, mixed> $actual
     * @param array<string, int|float|string> $expected
     *
     * @return array<string, string>
     */
    public static function diff(array $actual, array $expected): array
    {
        $changes = [];
        foreach (self::MANAGED as $facet) {
            $has = \array_key_exists($facet, $actual);
            $wants = \array_key_exists($facet, $expected);

            $change = match (true) {
                $has && $wants && $actual[$facet] === $expected[$facet] => null,
                $has && $wants => \sprintf('%s %s -> %s', $facet, self::show($actual[$facet]), self::show($expected[$facet])),
                $wants => \sprintf('+%s %s', $facet, self::show($expected[$facet])),
                $has => '-' . $facet,
                default => null,
            };

            if ($change !== null) {
                $changes[$facet] = $change;
            }
        }

        return $changes;
    }

    private static function format(mixed $format): ?string
    {
        if (!\is_string($format)) {
            return null;
        }

        return match (true) {
            $format === 'date-time', str_contains($format, 'date_time') => 'date-time',
            $format === 'date', str_contains($format, 'date_notime') => 'date',
            str_contains($format, 'email') => 'email',
            $format === 'uri' => 'uri',
            default => null,
        };
    }

    /**
     * A long string is cut, with a hash of the whole so that it stays distinct
     * in baseline keys.
     */
    private static function show(mixed $value): string
    {
        if (!\is_string($value)) {
            return \is_scalar($value) ? (string) $value : get_debug_type($value);
        }

        return mb_strlen($value) > 40
            ? \sprintf('"%s..." #%s', mb_substr($value, 0, 30), substr(md5($value), 0, 8))
            : '"' . $value . '"';
    }
}
