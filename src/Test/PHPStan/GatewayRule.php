<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Test\PHPStan;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Shopware\PayPalSDK\Contract\Gateway\GatewayInterface;

/**
 * @internal
 *
 * @implements Rule<InClassNode>
 */
class GatewayRule implements Rule
{
    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    /**
     * @param InClassNode $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $errors = [];
        $class = $node->getClassReflection();

        if (!\str_starts_with($class->getName(), 'Shopware\PayPalSDK\Gateway')) {
            return $errors;
        }

        if (!$class->implementsInterface(GatewayInterface::class)) {
            $errors[] = RuleErrorBuilder::message('Should implement ' . GatewayInterface::class)->identifier('gateway.interface')->build();
        }

        return $errors;
    }
}
