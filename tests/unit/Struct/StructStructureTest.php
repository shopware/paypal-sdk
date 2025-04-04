<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Struct;

use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Struct\Collection;
use Shopware\PayPalSDK\Struct\ConstantsV1;
use Shopware\PayPalSDK\Struct\ConstantsV2;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\OAuthCredentials;
use Shopware\PayPalSDK\Struct\V1\Token;
use Symfony\Component\Finder\Finder;

/**
 * @internal
 */
class StructStructureTest extends TestCase
{
    private const DIR = __DIR__ . '/../../../src/Struct';

    private const EXCLUSIONS = [
        Collection::class,
        OAuthCredentials::class,
        Token::class,
        ConstantsV1::class,
        ConstantsV2::class,
    ];

    public function testAllStructsHaveSettersAndGetters(): void
    {
        $errors = [];

        foreach ($this->getAllStructs(self::DIR) as $structClass) {
            if ($this->isExcluded($structClass)) {
                continue;
            }

            $reflectionClass = new \ReflectionClass($structClass);
            if ($reflectionClass->isAbstract()) {
                continue;
            }

            $struct = new $structClass();

            static::assertInstanceOf(Struct::class, $struct);
            static::assertInstanceOf($structClass, $struct);

            foreach ($reflectionClass->getProperties() as $property) {
                $fqdn = \sprintf('%s::%s', $structClass, $property->getName());

                if (!$property->isProtected()) {
                    $errors[$fqdn][] = 'Should have a visibility of `protected`';
                }

                if (!$propertyType = $property->getType()) {
                    $errors[$fqdn][] = 'Have a native type';
                }

                if ($propertyType && $propertyType->allowsNull() && !$property->isInitialized($struct)) {
                    $errors[$fqdn][] = 'Should be initialized with `null`';
                }

                $propertyName = \ucfirst(\str_replace('_', '', $property->getName()));
                $propertyTypeName = $this->getTypeName($propertyType);

                $isBool = $propertyType instanceof \ReflectionNamedType && $propertyType->getName() === 'bool';
                $getter = ($isBool ? 'is' : 'get') . $propertyName;
                $setter = 'set' . $propertyName;

                if (!$reflectionClass->hasMethod($getter)) {
                    $errors[$fqdn][] = 'Should have a getter `' . $getter . '`';
                } else {
                    $returnType = $reflectionClass->getMethod($getter)->getReturnType();

                    if ($propertyTypeName !== $this->getTypeName($returnType)) {
                        $errors[$fqdn][] = 'Should have right return type for getter `' . $getter . '`';
                    }
                }

                if (!$reflectionClass->hasMethod($setter)) {
                    $errors[$fqdn][] = 'Should have a setter `' . $setter . '`';
                } else {
                    $parameters = $reflectionClass->getMethod($setter)->getParameters();

                    if (!$parameters || \count($parameters) !== 1) {
                        $errors[$fqdn][] = 'Should have one parameter for setter `' . $setter . '`';
                    } elseif (!\str_contains($this->getTypeName($parameters[0]->getType()) ?? '', $propertyTypeName ?? '')) {
                        $errors[$fqdn][] = 'Should have right parameter type for setter `' . $setter . '`';
                    }
                }

                $value = $this->getMockValue($propertyType);
                $struct->{$setter}($value);

                if ($value !== $struct->{$getter}()) {
                    $errors[$fqdn][] = 'Should same value through getter as set via setter';
                }
            }
        }

        static::assertEmpty($errors, print_r($errors, true));
    }

    /**
     * @return class-string[]
     */
    private function getAllStructs(string $path): array
    {
        $finderFiles = Finder::create()->files()->in($path)->name('*.php');
        $classNames = [];
        foreach ($finderFiles as $finderFile) {
            $fileName = $finderFile->getRealPath();
            $className = $this->getFullNamespace($fileName) . '\\' . $this->getClassName($fileName);

            if (!\class_exists($className)) {
                continue;
            }

            $classNames[] = $className;
        }

        return $classNames;
    }

    private function getClassName(string $fileName): string
    {
        $directoriesAndFileName = \explode('/', $fileName);
        $fileName = \array_pop($directoriesAndFileName);
        $nameAndExtension = \explode('.', $fileName);

        return \array_shift($nameAndExtension);
    }

    private function getFullNamespace(string $fileName): string
    {
        $lines = \file($fileName) ?: [];
        /** @var string[] $array */
        $array = \preg_grep('/^namespace /', $lines) ?: [];
        $namespaceLine = \array_shift($array);
        static::assertNotNull($namespaceLine);
        $match = [];
        \preg_match('/^namespace (.*);$/', $namespaceLine, $match);

        return \array_pop($match) ?? '';
    }

    private function isExcluded(string $structClass): bool
    {
        foreach (self::EXCLUSIONS as $exclusion) {
            if (\is_a($structClass, $exclusion, true)) {
                return true;
            }
        }

        return false;
    }

    private function getTypeName(?\ReflectionType $type): ?string
    {
        if ($type instanceof \ReflectionUnionType) {
            return \implode('|', \array_map($this->getTypeName(...), $type->getTypes()));
        }

        if ($type instanceof \ReflectionNamedType) {
            return $type->getName();
        }

        return null;
    }

    private function getMockValue(?\ReflectionType $propertyType): mixed
    {
        if ($propertyType === null) {
            return null;
        }

        if ($propertyType instanceof \ReflectionUnionType) {
            $types = $propertyType->getTypes();
            $propertyType = $types[0];
        }

        static::assertInstanceOf(\ReflectionNamedType::class, $propertyType);

        return match ($propertyType->getName()) {
            'string' => 'test',
            'int' => 1,
            'float' => 1.0,
            'bool' => true,
            'array' => [],
            'object' => new \stdClass(),
            // @phpstan-ignore-next-line
            default => $this->createMock($propertyType->getName()),
        };
    }
}
