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

/**
 * Sets or removes {@see Facets::MANAGED} arguments of `#[OA\Property]`
 * attributes. Everything else is printed as written.
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
     * A facet bound to a constant is an SDK limit and kept. New facets go
     * before `nullable`, which stays last.
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
                    $arg = new Arg(self::node($value), name: new Identifier($facet));
                    array_splice($attribute->args, self::argIndex($attribute, 'nullable') ?? \count($attribute->args), 0, [$arg]);
                    $changed = true;
                }

                continue;
            }

            $current = self::literal($attribute->args[$index]->value);
            if ($current === null || $current === $value) {
                continue;
            }

            if ($value === null) {
                array_splice($attribute->args, $index, 1);
            } else {
                $attribute->args[$index]->value = self::node($value);
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

    private static function node(int|float|string $value): Expr
    {
        return match (true) {
            \is_int($value) => new Scalar\Int_($value),
            \is_float($value) => new Scalar\Float_($value),
            default => new Scalar\String_($value),
        };
    }

    /**
     * The value of a literal, null for any other expression (e.g. a constant).
     */
    private static function literal(Expr $value): int|float|string|null
    {
        return match (true) {
            $value instanceof Scalar\Int_, $value instanceof Scalar\Float_, $value instanceof Scalar\String_ => $value->value,
            $value instanceof Expr\UnaryMinus && $value->expr instanceof Scalar\Int_, $value instanceof Expr\UnaryMinus && $value->expr instanceof Scalar\Float_ => -$value->expr->value,
            default => null,
        };
    }
}
