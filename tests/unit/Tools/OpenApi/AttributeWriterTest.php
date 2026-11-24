<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Tools\OpenApi;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Tools\OpenApi\AttributeWriter;

/**
 * @internal
 */
#[CoversClass(AttributeWriter::class)]
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
