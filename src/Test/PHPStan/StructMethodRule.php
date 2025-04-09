<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Test\PHPStan;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassMethodNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\VerbosityLevel;
use Shopware\PayPalSDK\Struct\Struct;

/**
 * @internal
 *
 * @implements Rule<InClassMethodNode>
 */
class StructMethodRule implements Rule
{
    private const EXCLUSIONS = [Struct::class];

    public function getNodeType(): string
    {
        return InClassMethodNode::class;
    }

    /**
     * @param InClassMethodNode $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $errors = [];
        $class = $node->getClassReflection();
        $method = $node->getMethodReflection();

        if (!$class->is(Struct::class) || !\str_starts_with($class->getName(), 'Shopware\PayPalSDK\Struct') || \in_array($class->getName(), self::EXCLUSIONS, true)) {
            return $errors;
        }

        $matches = [];
        \preg_match_all('/^(is|set|get)(?<name>.*)/', $method->getName(), $matches);
        $propertyName = \lcfirst($matches['name'][0] ?? '');

        if (!$propertyName || !$class->hasProperty($propertyName)) {
            return $errors;
        }

        if (!$method->isPublic()) {
            $errors[] = self::message($node, 'should be public', 'visibility');
        }

        $property = $class->getProperty($propertyName, $scope);
        $propertyType = $property->getReadableType();

        if (!$property->hasNativeType()) {
            return $errors;
        }

        if (\str_starts_with($method->getName(), 'set')) {
            if ($method->getReturnType()->isVoid()->no()) {
                $errors[] = self::message($node, 'should return void', 'setter.returnType');
            }

            $paramCount = \count($method->getParameters());

            if ($paramCount !== 1) {
                $errors[] = self::message($node, 'should have a setter with one parameter', 'setter.parameterCount');
            } else {
                $paramType = $method->getParameters()[0]->getType();

                if ($propertyType->accepts($paramType, true)->no()) {
                    $errors[] = self::message($node, 'should have first param accept ' . $propertyType->describe(VerbosityLevel::typeOnly()) . ' but accepts ' . $paramType->describe(VerbosityLevel::typeOnly()), 'setter.parameterType');
                }
            }
        } else {
            if ($propertyType->accepts($method->getReturnType(), true)->no()) {
                $errors[] = self::message($node, 'should return ' . $propertyType->describe(VerbosityLevel::typeOnly()) . ' but returns ' . $method->getReturnType()->describe(VerbosityLevel::typeOnly()), 'getter.returnType');
            }

            if (\count($method->getParameters()) !== 0) {
                $errors[] = self::message($node, 'should have no parameters', 'getter.parameterCount');
            }
        }

        return $errors;
    }

    private static function message(InClassMethodNode $node, string $shouldMessage, string $identifier): IdentifierRuleError
    {
        return RuleErrorBuilder::message('Method ' . $node->getMethodReflection()->getName() . ' ' . $shouldMessage)
            ->identifier('struct.method.' . $identifier)
            ->build();
    }
}
