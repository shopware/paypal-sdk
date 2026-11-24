<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tools\OpenApi;

use OpenApi\Attributes\Property;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar;
use PhpParser\Node\Stmt;
use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\CloningVisitor;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;
use Shopware\PayPalSDK\Struct\Pattern;

/**
 * Sets or removes {@see Facets::MANAGED} arguments of `#[OA\Property]`
 * attributes. A pattern is written as its {@see Pattern} constant if there is
 * one. Everything else is printed as written.
 */
final class AttributeWriter
{
    /**
     * @param array<string, array<string, array<string, array<string, int|float|string|null>>>> $fixes file => class => property => facet => value, null removes it
     *
     * @return int number of attributes changed
     */
    public static function apply(array $fixes): int
    {
        $parser = (new ParserFactory())->createForHostVersion();
        $printer = new class extends Standard {
            protected function initializeListInsertionMap(): void
            {
                parent::initializeListInsertionMap();
                // missing upstream; without it a new argument reprints the whole attribute
                $this->listInsertionMap[Node\Attribute::class . '->args'] = ', ';
            }
        };

        $changed = 0;
        foreach ($fixes as $file => $classes) {
            $code = (string) file_get_contents($file);
            $original = $parser->parse($code) ?? [];
            $tokens = $parser->getTokens();
            $ast = (new NodeTraverser(new CloningVisitor()))->traverse($original);
            (new NodeTraverser(new NameResolver(null, ['replaceNodes' => false])))->traverse($ast);

            $fileChanged = 0;
            foreach ((new NodeFinder())->findInstanceOf($ast, Stmt\Class_::class) as $classNode) {
                foreach ($classNode->getProperties() as $propertyNode) {
                    $facets = $classes[(string) $classNode->namespacedName][$propertyNode->props[0]->name->toString()] ?? null;
                    $attribute = self::propertyAttribute($propertyNode);
                    if ($facets !== null && $attribute !== null && self::applyFacets($attribute, $facets)) {
                        ++$fileChanged;
                    }
                }
            }

            if ($fileChanged > 0) {
                self::importPattern($ast);
                file_put_contents($file, $printer->printFormatPreserving($ast, $original, $tokens));
                $changed += $fileChanged;
            }
        }

        return $changed;
    }

    private static function propertyAttribute(Stmt\Property $property): ?Node\Attribute
    {
        foreach ($property->attrGroups as $group) {
            foreach ($group->attrs as $attribute) {
                $resolved = $attribute->name->getAttribute('resolvedName');
                if ($resolved instanceof Name && $resolved->toString() === Property::class) {
                    return $attribute;
                }
            }
        }

        return null;
    }

    /**
     * A facet bound to a constant other than a {@see Pattern} is an SDK limit
     * and kept. New facets go before `nullable`, which stays last.
     *
     * @param array<string, int|float|string|null> $facets
     */
    private static function applyFacets(Node\Attribute $attribute, array $facets): bool
    {
        $changed = false;
        foreach ($facets as $facet => $value) {
            $index = self::argIndex($attribute, $facet);

            if ($index === null) {
                if ($value !== null) {
                    $arg = new Arg(self::node($facet, $value), name: new Identifier($facet));
                    array_splice($attribute->args, self::argIndex($attribute, 'nullable') ?? \count($attribute->args), 0, [$arg]);
                    $changed = true;
                }

                continue;
            }

            $current = self::value($attribute->args[$index]->value);
            if ($current === null || $current === $value) {
                continue;
            }

            if ($value === null) {
                array_splice($attribute->args, $index, 1);
            } else {
                $attribute->args[$index]->value = self::node($facet, $value);
            }
            $changed = true;
        }

        return $changed;
    }

    private static function argIndex(Node\Attribute $attribute, string $name): ?int
    {
        foreach ($attribute->args as $index => $arg) {
            if ($arg->name?->toString() === $name) {
                return $index;
            }
        }

        return null;
    }

    private static function node(string $facet, int|float|string $value): Expr
    {
        $pattern = $facet === 'pattern' && \is_string($value) ? Facets::patternName($value) : null;

        return match (true) {
            $pattern !== null => new Expr\ClassConstFetch(new Name('Pattern'), $pattern),
            \is_int($value) => new Scalar\Int_($value),
            \is_float($value) => new Scalar\Float_($value),
            default => new Scalar\String_($value),
        };
    }

    /**
     * The value of a literal or {@see Pattern} constant, null for any other
     * expression.
     */
    private static function value(Expr $value): int|float|string|null
    {
        return match (true) {
            $value instanceof Scalar\Int_, $value instanceof Scalar\Float_, $value instanceof Scalar\String_ => $value->value,
            $value instanceof Expr\UnaryMinus && $value->expr instanceof Scalar\Int_, $value instanceof Expr\UnaryMinus && $value->expr instanceof Scalar\Float_ => -$value->expr->value,
            $value instanceof Expr\ClassConstFetch => self::patternValue($value),
            default => null,
        };
    }

    private static function patternValue(Expr\ClassConstFetch $fetch): ?string
    {
        $class = $fetch->class->getAttribute('resolvedName');
        if (!$class instanceof Name || $class->toString() !== Pattern::class || !$fetch->name instanceof Identifier) {
            return null;
        }

        $value = \constant(Pattern::class . '::' . $fetch->name->toString());

        return \is_string($value) ? $value : null;
    }

    /**
     * Adds `use ...\Pattern;` among the imports if a written facet needs it.
     *
     * @param array<Node> $ast
     */
    private static function importPattern(array $ast): void
    {
        $finder = new NodeFinder();
        $namespace = $finder->findFirstInstanceOf($ast, Stmt\Namespace_::class);
        $needed = $finder->findFirst($ast, static fn (Node $node) => $node instanceof Expr\ClassConstFetch
            && $node->class instanceof Name
            && $node->class->toString() === 'Pattern'
            && $node->class->getAttribute('resolvedName') === null);

        if ($namespace === null || $needed === null || $namespace->name?->toString() === (new \ReflectionClass(Pattern::class))->getNamespaceName()) {
            return;
        }

        $position = 0;
        foreach ($namespace->stmts as $index => $stmt) {
            if (!$stmt instanceof Stmt\Use_) {
                continue;
            }
            $name = $stmt->uses[0]->name->toString();
            if ($name === Pattern::class) {
                return;
            }
            if (strcasecmp($name, Pattern::class) < 0) {
                $position = $index + 1;
            }
        }

        array_splice($namespace->stmts, $position, 0, [new Stmt\Use_([new Node\UseItem(new Name(Pattern::class))])]);
    }
}
