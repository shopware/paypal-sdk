<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tools\OpenApi;

/**
 * An OpenAPI document: its component schemas and operations.
 */
final readonly class Spec
{
    /**
     * @param array<mixed> $schemas component schema name => schema definition
     * @param array<mixed> $paths the document's `paths` object
     */
    private function __construct(
        public string $name,
        private array $schemas,
        private array $paths,
    ) {}

    public static function parse(string $name, string $json): self
    {
        $data = json_decode($json, true, 512, \JSON_THROW_ON_ERROR);
        if (!\is_array($data) || !\is_array($data['paths'] ?? null)) {
            throw new \RuntimeException(\sprintf('%s is not an OpenAPI document', $name));
        }

        $components = \is_array($data['components'] ?? null) ? $data['components'] : [];

        return new self(
            $name,
            \is_array($components['schemas'] ?? null) ? $components['schemas'] : [],
            $data['paths'],
        );
    }

    /**
     * The operation for a method and path. Path parameters match by position
     * (`/orders/{id}` equals `/orders/{order_id}`). Bodies are raw schemas;
     * `examples` holds the 2xx response examples, keyed by "{status} {name}".
     *
     * @return array{request: array<mixed>|null, responses: list<array<mixed>>, examples: array<string, array<mixed>>}|null
     */
    public function operation(string $method, string $path): ?array
    {
        $normalise = static fn (string $p): string => (string) preg_replace('/\{[^}]+\}/', '{}', $p);

        foreach ($this->paths as $specPath => $item) {
            if (!\is_array($item) || $normalise((string) $specPath) !== $normalise($path)) {
                continue;
            }

            $operation = $item[strtolower($method)] ?? null;
            if (!\is_array($operation)) {
                return null;
            }

            $responses = [];
            $examples = [];
            foreach (\is_array($operation['responses'] ?? null) ? $operation['responses'] : [] as $status => $response) {
                if (!str_starts_with((string) $status, '2')) {
                    continue;
                }

                if (($schema = self::bodySchema($response)) !== null) {
                    $responses[] = $schema;
                }

                foreach (self::bodyExamples($response) as $name => $example) {
                    $examples[$status . ' ' . $name] = $example;
                }
            }

            return ['request' => self::bodySchema($operation['requestBody'] ?? null), 'responses' => $responses, 'examples' => $examples];
        }

        return null;
    }

    /**
     * "#/components/schemas/foo" => "foo"
     */
    public static function refName(string $ref): string
    {
        return substr($ref, (int) strrpos($ref, '/') + 1);
    }

    /**
     * @return array<mixed>|null
     */
    public function schema(string $name): ?array
    {
        $schema = $this->schemas[$name] ?? null;

        return \is_array($schema) ? $schema : null;
    }

    /**
     * The component a schema only refers to, following aliases (a component
     * that is itself only a reference). Null for an inline schema.
     *
     * @param array<mixed> $schema
     */
    public function schemaName(array $schema): ?string
    {
        $name = null;
        $seen = [];
        while (($ref = self::soleRef($schema)) !== null && !isset($seen[$name = self::refName($ref)])) {
            $seen[$name] = true;
            $schema = $this->schema($name) ?? [];
        }

        return $name;
    }

    /**
     * The pointer of a `$ref`, or of an `allOf` whose only member that is not
     * an annotation (e.g. a description) is a `$ref`.
     *
     * @param array<mixed> $schema
     */
    public static function soleRef(array $schema): ?string
    {
        if (\is_string($schema['$ref'] ?? null)) {
            return $schema['$ref'];
        }

        if (!\is_array($schema['allOf'] ?? null)) {
            return null;
        }

        $refs = [];
        foreach ($schema['allOf'] as $member) {
            if (!\is_array($member)) {
                continue;
            }

            $nested = self::soleRef($member);
            if ($nested !== null) {
                $refs[] = $nested;

                continue;
            }

            if (isset($member['type']) || isset($member['properties']) || isset($member['items']) || isset($member['enum'])) {
                return null;
            }
        }

        return \count($refs) === 1 ? $refs[0] : null;
    }

    /**
     * Follows `$ref`s and merges `allOf` members into one schema.
     *
     * @param array<mixed> $schema
     * @param array<string, true> $seen schema names on the current path, to stop at cycles
     *
     * @return array<mixed>
     */
    public function flatten(array $schema, array $seen = []): array
    {
        if (\is_string($schema['$ref'] ?? null)) {
            $name = self::refName($schema['$ref']);
            if (isset($seen[$name])) {
                return [];
            }

            return $this->flatten($this->schema($name) ?? [], [...$seen, $name => true]);
        }

        if (!\is_array($schema['allOf'] ?? null)) {
            return $schema;
        }

        $properties = \is_array($schema['properties'] ?? null) ? $schema['properties'] : [];
        $required = \is_array($schema['required'] ?? null) ? $schema['required'] : [];
        $scalar = null;

        foreach ($schema['allOf'] as $member) {
            if (!\is_array($member)) {
                continue;
            }

            $member = $this->flatten($member, $seen);

            if (\is_array($member['properties'] ?? null)) {
                $properties = [...$properties, ...$member['properties']];
            }
            if (\is_array($member['required'] ?? null)) {
                $required = [...$required, ...$member['required']];
            }
            if (\in_array($member['type'] ?? null, ['string', 'integer', 'number', 'boolean', 'array'], true)) {
                $scalar = $member;
            }
        }

        unset($schema['allOf']);

        // an `allOf` around one scalar, e.g. `[{$ref: date_time}, {description}]`
        if ($properties === [] && $scalar !== null) {
            return [...$scalar, ...$schema];
        }

        $schema['type'] = 'object';
        $schema['properties'] = $properties;
        if ($required !== []) {
            $schema['required'] = array_values(array_unique($required));
        }

        return $schema;
    }

    /**
     * @return array<mixed>|null
     */
    private static function bodySchema(mixed $body): ?array
    {
        if (!\is_array($body) || !\is_array($body['content'] ?? null)) {
            return null;
        }

        foreach ($body['content'] as $media) {
            if (\is_array($media) && \is_array($media['schema'] ?? null)) {
                return $media['schema'];
            }
        }

        return null;
    }

    /**
     * @return array<string, array<mixed>>
     */
    private static function bodyExamples(mixed $body): array
    {
        if (!\is_array($body) || !\is_array($body['content'] ?? null)) {
            return [];
        }

        $examples = [];
        foreach ($body['content'] as $media) {
            if (!\is_array($media)) {
                continue;
            }

            if (\is_array($media['example'] ?? null)) {
                $examples['example'] = $media['example'];
            }

            foreach (\is_array($media['examples'] ?? null) ? $media['examples'] : [] as $name => $example) {
                if (\is_array($example) && \is_array($example['value'] ?? null)) {
                    $examples[(string) $name] = $example['value'];
                }
            }
        }

        return $examples;
    }
}
