<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Test\PHPStan;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\ClassPropertyNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Shopware\PayPalSDK\Struct\Collection;
use Shopware\PayPalSDK\Struct\ConstantsV1;
use Shopware\PayPalSDK\Struct\ConstantsV2;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Util\CaseConverter;

/**
 * @implements Rule<ClassPropertyNode>
 */
class StructRule implements Rule
{
    private const EXCLUSIONS = [
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

        if ($node->isStatic() || !\str_starts_with($class->getName(), 'Shopware\PayPalSDK\Struct') || \in_array($class->getName(), self::EXCLUSIONS, true)) {
            return $errors;
        }

        if (!$node->isProtected()) {
            $errors[] = self::message($node, 'should be protected', 'protected');
        }

        if ($node->getName() !== ($camelCaseName = CaseConverter::denormalize($node->getName()))) {
            $errors[] = self::message($node, 'should have a camelCased name of "' . $camelCaseName . '"', 'name');
        }

        if ($type = ($node->getPhpDocType() ?? $node->getNativeType())) {
            if (($type->isNull()->maybe() || $type->isNull()->yes()) && !$node->getDefault()) {
                $errors[] = self::message($node, 'is nullable and should have an default value', 'default');
            }

            $setter = 'set' . \ucfirst($node->getName());

            if (!$class->hasMethod($setter)) {
                $errors[] = self::message($node, 'should have a setter "' . $setter . '"', 'setter');
            }

            $getter = match ((bool) \preg_match('/^is[A-Z]/', $node->getName())) {
                true => $node->getName(),
                false => ($type->isBoolean()->or($type->isNull())->yes() ? 'is' : 'get') . \ucfirst($node->getName()),
            };

            if (!$class->hasMethod($getter)) {
                $errors[] = self::message($node, 'should have a getter "' . $getter . '"', 'getter');
            }
        } else {
            $errors[] = self::message($node, 'should have a type', 'type');
        }

        return $errors;
    }

    private static function message(ClassPropertyNode $node, string $shouldMessage, string $identifier): IdentifierRuleError
    {
        return RuleErrorBuilder::message('Property $' . $node->getName() . ' ' . $shouldMessage)
            ->identifier('struct.property.' . $identifier)
            ->build();
    }
}
