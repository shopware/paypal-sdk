<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Struct;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Struct\Pattern;

/**
 * @internal
 */
#[CoversNothing]
class PatternTest extends TestCase
{
    public function testEveryPatternIsAConstant(): void
    {
        $literals = [];
        foreach (self::structFiles() as $file => $code) {
            preg_match_all('/\bpattern:\s*+(?!Pattern::)(\S{1,40})/', $code, $matches);
            foreach ($matches[1] as $literal) {
                $literals[] = $file . ': ' . $literal;
            }
        }

        static::assertSame([], $literals, 'add the pattern to ' . Pattern::class);
    }

    public function testEveryConstantIsUsed(): void
    {
        $used = [];
        foreach (self::structFiles() as $code) {
            preg_match_all('/\bpattern:\s*Pattern::(\w+)/', $code, $matches);
            $used = [...$used, ...$matches[1]];
        }

        $constants = array_keys((new \ReflectionClass(Pattern::class))->getConstants());

        static::assertSame([], array_values(array_diff($constants, $used)));
    }

    /**
     * @return \Generator<string, string>
     */
    private static function structFiles(): \Generator
    {
        $pattern = (new \ReflectionClass(Pattern::class))->getFileName();
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(\dirname((string) $pattern), \FilesystemIterator::SKIP_DOTS));
        foreach ($files as $file) {
            if ($file instanceof \SplFileInfo && $file->getExtension() === 'php' && $file->getPathname() !== $pattern) {
                yield $file->getPathname() => (string) file_get_contents($file->getPathname());
            }
        }
    }
}
