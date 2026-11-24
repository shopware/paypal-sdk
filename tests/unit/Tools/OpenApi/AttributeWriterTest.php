<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Tools\OpenApi;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Struct\Pattern;
use Shopware\PayPalSDK\Tools\OpenApi\AttributeWriter;
use Shopware\PayPalSDK\Tools\OpenApi\Facets;

/**
 * @internal
 */
#[CoversClass(AttributeWriter::class)]
#[UsesClass(Facets::class)]
class AttributeWriterTest extends TestCase
{
    private const string SOURCE = <<<'PHP'
        <?php declare(strict_types=1);

        namespace Fixture;

        use OpenApi\Attributes as OA;

        class Subject
        {
            #[OA\Property(type: 'string', maxLength: self::MAX_LENGTH_NAME, nullable: true)]
            protected ?string $name;

            #[OA\Property(type: 'string', maxLength: 3, minLength: 1)]
            protected string $code;

            #[OA\Property(
                type: 'string',
                // kept
                maxLength: 3,
            )]
            protected string $multiLine;
        }

        class Other
        {
            #[OA\Property(type: 'string', maxLength: 3, minLength: 1)]
            protected string $code;
        }
        PHP;

    private string $file;

    protected function setUp(): void
    {
        $this->file = (string) tempnam(sys_get_temp_dir(), 'attribute-writer');
        file_put_contents($this->file, self::SOURCE);
    }

    protected function tearDown(): void
    {
        @unlink($this->file);
    }

    public function testSetsAndRemovesLiteralFacets(): void
    {
        $count = $this->apply(['code' => ['maxLength' => 14, 'minLength' => null, 'pattern' => '^[a-z]+$']]);

        static::assertSame(1, $count);
        static::assertStringContainsString("#[OA\\Property(type: 'string', maxLength: 14, pattern: '^[a-z]+\$')]\n    protected string \$code;", $this->code());
    }

    public function testKeepsConstantBoundFacetsAndNullableLast(): void
    {
        $this->apply(['name' => ['maxLength' => 127, 'minLength' => 1]]);

        static::assertStringContainsString('#[OA\\Property(type: \'string\', maxLength: self::MAX_LENGTH_NAME, minLength: 1, nullable: true)]', $this->code());
    }

    public function testKeepsMultiLineLayoutAndComments(): void
    {
        $this->apply(['multiLine' => ['maxLength' => 5, 'minLength' => 1]]);

        static::assertStringContainsString(<<<'PHP'
                #[OA\Property(
                    type: 'string',
                    // kept
                    maxLength: 5,
                    minLength: 1,
                )]
            PHP, $this->code());
    }

    public function testOnlyChangesTheGivenClass(): void
    {
        $this->apply(['code' => ['maxLength' => 14]]);

        static::assertStringEndsWith("class Other\n{\n    #[OA\\Property(type: 'string', maxLength: 3, minLength: 1)]\n    protected string \$code;\n}", $this->code());
    }

    public function testLeavesTheFileAloneWithoutChanges(): void
    {
        static::assertSame(0, $this->apply(['code' => ['maxLength' => 3]]));
        static::assertSame(self::SOURCE, $this->code());
    }

    public function testWrittenPatternIsTheSpecPattern(): void
    {
        $pattern = '^[a-z\']+\d$';
        $this->apply(['code' => ['pattern' => $pattern]]);

        static::assertSame(1, preg_match('/pattern: (\'(?:[^\'\\\\]|\\\\.)*\')/', $this->code(), $match));
        static::assertSame($pattern, eval('return ' . $match[1] . ';'));
    }

    public function testWritesAKnownPatternAsItsConstant(): void
    {
        $this->apply(['code' => ['pattern' => Pattern::UPPER_ALNUM]]);

        static::assertStringContainsString("use OpenApi\\Attributes as OA;\nuse Shopware\\PayPalSDK\\Struct\\Pattern;\n", $this->code());
        static::assertStringContainsString("maxLength: 3, minLength: 1, pattern: Pattern::UPPER_ALNUM)]\n    protected string \$code;", $this->code());
    }

    public function testReplacesAndRemovesPatternConstants(): void
    {
        file_put_contents($this->file, <<<'PHP'
            <?php declare(strict_types=1);

            namespace Fixture;

            use OpenApi\Attributes as OA;
            use Shopware\PayPalSDK\Struct\Pattern;

            class Subject
            {
                #[OA\Property(type: 'string', pattern: Pattern::COUNTRY_CODE)]
                protected string $country;

                #[OA\Property(type: 'string', pattern: Pattern::DATE)]
                protected string $date;

                #[OA\Property(type: 'string', pattern: Pattern::UPPER_ALNUM)]
                protected string $code;
            }
            PHP);

        $count = $this->apply([
            'country' => ['pattern' => Pattern::ISO_COUNTRY_CODE],
            'date' => ['pattern' => null],
            'code' => ['pattern' => Pattern::UPPER_ALNUM],
        ]);

        static::assertSame(2, $count);
        static::assertStringContainsString("pattern: Pattern::ISO_COUNTRY_CODE)]\n    protected string \$country;", $this->code());
        static::assertStringContainsString("#[OA\\Property(type: 'string')]\n    protected string \$date;", $this->code());
        static::assertSame(1, substr_count($this->code(), 'use Shopware\\PayPalSDK\\Struct\\Pattern;'));
    }

    /**
     * @param array<string, array<string, int|float|string|null>> $properties
     */
    private function apply(array $properties): int
    {
        return AttributeWriter::apply([$this->file => ['Fixture\Subject' => $properties]]);
    }

    private function code(): string
    {
        return (string) file_get_contents($this->file);
    }
}
