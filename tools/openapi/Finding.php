<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tools\OpenApi;

/**
 * One way a struct disagrees with the schema(s) it stands in for.
 */
final readonly class Finding
{
    /**
     * The SDK type cannot hold the spec's value: assigning throws or loses data.
     */
    public const string TYPE = 'type';

    /**
     * The getter throws on an absent field that a full response example omits.
     */
    public const string GETTER = 'getter';

    /**
     * A {@see Facets::MANAGED} argument of `#[OA\Property]` differs from the spec.
     */
    public const string ATTRIBUTE = 'attribute';

    /**
     * An enum value only one of SDK and spec knows.
     */
    public const string ENUM = 'enum';

    /**
     * The property is read or written under another name than the spec's field.
     */
    public const string FIELD = 'field';

    /**
     * The SDK models a field no paired schema defines.
     */
    public const string SDK_ONLY = 'sdk-only';

    /**
     * No operation in {@see Roots} reaches the struct, and it is not listed as uncovered.
     */
    public const string UNPAIRED = 'unpaired';

    /**
     * A paired schema defines a field the SDK does not model.
     */
    public const string SPEC_ONLY = 'spec-only';

    /**
     * Kinds that fail the check.
     */
    public const array GATING = [self::TYPE, self::GETTER, self::ATTRIBUTE, self::ENUM, self::FIELD, self::SDK_ONLY, self::UNPAIRED];

    public const array KINDS = [...self::GATING, self::SPEC_ONLY];

    /**
     * @param class-string $class
     * @param string $detail tells apart findings of one kind on the same property
     * @param array<string, int|float|string|null> $fix facets `--fix` writes, null removes one
     */
    public function __construct(
        public string $kind,
        public string $class,
        public string $property,
        public string $message,
        public string $detail = '',
        public array $fix = [],
    ) {}

    public static function shortClass(string $class): string
    {
        return str_replace('Shopware\PayPalSDK\Struct\\', '', ltrim($class, '\\'));
    }

    /**
     * Baseline key, e.g. `enum V2\Order\Foo::$bar +BAZ`.
     */
    public function id(): string
    {
        $subject = self::shortClass($this->class) . ($this->property === '' ? '' : '::$' . $this->property);

        return trim(\sprintf('%s %s %s', $this->kind, $subject, $this->detail));
    }
}
