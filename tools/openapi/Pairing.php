<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tools\OpenApi;

use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Util\CaseConverter;

/**
 * A schema a struct stands in for.
 */
final readonly class Pairing implements \Stringable
{
    /**
     * @param string $schema component name, or the position of an inline schema
     * @param array<mixed> $definition flattened schema
     */
    public function __construct(
        public Spec $spec,
        public string $schema,
        public bool $isRequest,
        public array $definition,
    ) {}

    public function __toString(): string
    {
        return $this->schema . ($this->isRequest ? ' (req)' : '');
    }

    /**
     * @return list<string>
     */
    public function fields(): array
    {
        return array_map(strval(...), array_keys($this->properties()));
    }

    /**
     * The field a property is assigned from, matched like
     * {@see Struct::assign()} (`line1` pairs with `line1`, not `line_1`).
     */
    public function field(string $property): ?string
    {
        foreach ($this->fields() as $field) {
            if (CaseConverter::denormalize($field) === $property) {
                return $field;
            }
        }

        return null;
    }

    /**
     * @return array<mixed>|null
     */
    public function fieldSchema(string $property): ?array
    {
        $field = $this->field($property);
        $schema = $field === null ? null : $this->properties()[$field];

        return \is_array($schema) ? $schema : null;
    }

    /**
     * {@see self::fieldSchema()} flattened, and its `items` too.
     *
     * @return array<mixed>|null
     */
    public function flatFieldSchema(string $property): ?array
    {
        $schema = $this->fieldSchema($property);
        if ($schema === null) {
            return null;
        }

        $schema = $this->spec->flatten($schema);
        if (\is_array($schema['items'] ?? null)) {
            $schema['items'] = $this->spec->flatten($schema['items']);
        }

        return $schema;
    }

    /**
     * @return array<mixed>
     */
    private function properties(): array
    {
        return \is_array($this->definition['properties'] ?? null) ? $this->definition['properties'] : [];
    }
}
