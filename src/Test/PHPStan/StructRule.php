<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Test\PHPStan;

use OpenApi\Attributes as OA;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\ClassPropertyNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\ObjectType;
use PHPStan\Type\TypeCombinator;
use Shopware\PayPalSDK\Struct\Collection;
use Shopware\PayPalSDK\Struct\ConstantsV1;
use Shopware\PayPalSDK\Struct\ConstantsV2;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Util\CaseConverter;

/**
 * @internal
 *
 * @implements Rule<ClassPropertyNode>
 */
class StructRule implements Rule
{
    private const array EXCLUSIONS = [
        Struct::class,
        Collection::class,
        ConstantsV1::class,
        ConstantsV2::class,
    ];

    public function getNodeType(): string
    {
        return ClassPropertyNode::class;
    }

    /**
     * @param ClassPropertyNode $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $errors = [];
        $class = $node->getClassReflection();

        if (
            $node->isStatic()
            || !\str_starts_with($class->getName(), 'Shopware\PayPalSDK\Struct')
            || str_contains($class->getName(), '\\Builder\\')
            || \in_array($class->getName(), self::EXCLUSIONS, true)
        ) {
            return $errors;
        }

        if (!$node->isProtected()) {
            $errors[] = self::message($node, 'should be protected', 'protected');
        }

        $type = ($node->getPhpDocType() ?? $node->getNativeType());

        if (!$node->getNativeType()) {
            $errors[] = self::message($node, 'should have a native type', 'type');
        } elseif (!$node->getNativeType()->isNull()->no() && $node->getDefault() === null) {
            $errors[] = self::message($node, 'is nullable and should default to null', 'default');
        } elseif ($node->getNativeType()->isNull()->no() && (new ObjectType(Collection::class))->isSuperTypeOf($node->getNativeType())->yes()) {
            $errors[] = self::message($node, 'is a collection and should be nullable', 'collection');
        } elseif ($node->getNativeType()->isNull()->no() && $node->getDefault() !== null && !self::mayHaveDefault($node)) {
            $errors[] = self::message($node, 'should not have a default, unless it is @internal or its #[OA\Property] enum allows only that value', 'nonNullDefault');
        }

        if ($node->getName() !== ($camelCaseName = CaseConverter::denormalize($node->getName()))) {
            $errors[] = self::message($node, 'should have a camelCased name of "' . $camelCaseName . '"', 'name');

            return $errors;
        }

        if ($type) {
            $setter = 'set' . \ucfirst($node->getName());

            if (!$class->hasMethod($setter)) {
                $errors[] = self::message($node, 'should have a setter "' . $setter . '"', 'setter');
            }

            $getter = match ((bool) \preg_match('/^is[A-Z]/', $node->getName())) {
                true => $node->getName(),
                false => (TypeCombinator::removeNull($type)->isBoolean()->yes() ? 'is' : 'get') . \ucfirst($node->getName()),
            };

            if (!$class->hasMethod($getter)) {
                $errors[] = self::message($node, 'should have a getter "' . $getter . '"', 'getter');
            }
        }

        return $errors;
    }

    /**
     * A default would be sent to PayPal although nobody chose it, or hide that PayPal left the field out.
     */
    private static function mayHaveDefault(ClassPropertyNode $node): bool
    {
        if (\str_contains($node->getPhpDoc() ?? '', '@internal')) {
            return true;
        }

        $property = $node->getClassReflection()->getNativeReflection()->getProperty($node->getName());
        foreach ($property->getAttributes(OA\Property::class) as $attribute) {
            $enum = $attribute->getArguments()['enum'] ?? null;

            return \is_array($enum) && $enum === [$property->getDefaultValue()];
        }

        return false;
    }

    private static function message(ClassPropertyNode $node, string $shouldMessage, string $identifier): IdentifierRuleError
    {
        return RuleErrorBuilder::message('Property $' . $node->getName() . ' ' . $shouldMessage)
            ->identifier('struct.property.' . $identifier)
            ->build();
    }
}
