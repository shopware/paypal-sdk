<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Struct;

use Monolog\Handler\TestHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\LogRecord;
use OpenApi\Annotations\OpenApi;
use OpenApi\Attributes\Property;
use OpenApi\Generator;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Util\CaseConverter;

/**
 * @internal
 */
class OpenAPISchemaTest extends TestCase
{
    public const DIRS = [
        __DIR__ . '/../../../src/Struct/V1',
        __DIR__ . '/../../../src/Struct/V2',
        __DIR__ . '/../../../src/Struct/V3',
    ];

    public const CONVERSION_EXCEPTIONS = [
        'Shopware' => null,
        'PayPalSDK' => 'paypal',
        'Struct' => null,
        'OAuth' => 'oauth',
        'ThreeDSecure' => '3d_secure',
    ];

    public const IGNORED_LOG_MESSAGES = [
        'Required @OA\PathItem() not found',
        'Required @OA\Info() not found',
    ];

    private OpenApi $oa;

    private TestHandler $logHandler;

    protected function setUp(): void
    {
        $this->logHandler = new TestHandler();
        $logger = new Logger('test', [$this->logHandler]);

        $oa = Generator::scan(self::DIRS, [
            'logger' => $logger,
        ]);

        static::assertInstanceOf(OpenApi::class, $oa, 'OpenAPI schema could not be generated.');

        $this->oa = $oa;
    }

    public function testAllPropertiesHaveSchemas(): void
    {
        $failures = [];

        foreach ($this->oa->_analysis->classes as $class => $classContext) {
            $schema = $this->oa->_analysis->getSchemaForSource($class);

            if (!$schema?->schema || $schema->schema === Generator::UNDEFINED) {
                continue;
            }

            if (!\class_exists($class)) {
                static::fail('Class ' . $class . ' does not exist.');
            }

            $refClass = new \ReflectionClass($class);
            foreach ($refClass->getProperties() as $property) {
                if (\count($property->getAttributes(Property::class)) === 0) {
                    $failures[] = $class . '::' . $property->getName() . ' is missing an OA\Property annotation.';
                }
            }
        }

        static::assertEmpty($failures, \implode(\PHP_EOL, $failures));
    }

    public function testGenerationWithoutWarningsOrErrors(): void
    {
        $failures = [];

        /** @var LogRecord $record */
        foreach ($this->logHandler->getRecords() as $record) {
            if ($record->level->isLowerThan(Level::Notice) || $this->ignorableRecord($record)) {
                continue;
            }

            $failures[] = $record->level->getName() . ': ' . $record->message;
        }

        static::assertEquals([], $failures);
    }

    public function testClassSchemaNaming(): void
    {
        $failures = [];

        foreach ($this->oa->_analysis->classes as $class => $classContext) {
            $schema = $this->oa->_analysis->getSchemaForSource($class);

            if (!$schema?->schema || $schema->schema === Generator::UNDEFINED) {
                continue;
            }

            $expectedSchema = $this->namespaceToSchema($class);

            if ($expectedSchema !== $schema->schema) {
                $failures[] = $class . ' was expected to have a schema name of "' . $expectedSchema . '" but has "' . $schema->schema . '" instead.';
            }
        }

        static::assertEmpty($failures, \implode(\PHP_EOL, $failures));
    }

    private function namespaceToSchema(string $fqdn): string
    {
        $parts = \explode('\\', $fqdn); // split fqdn into parts
        $parts = \array_map(fn ($part) => $this->camelToSnakeCase($part), $parts); // normalize each part to snake_case
        $parts = \array_filter($parts, fn ($part) => (bool) $part); // remove empty parts

        return \implode('_', $parts);
    }

    private function camelToSnakeCase(string $input): string
    {
        $input = \str_replace(
            \array_keys(self::CONVERSION_EXCEPTIONS),
            \array_map(
                static fn (?string $v) => (string) $v,
                \array_values(self::CONVERSION_EXCEPTIONS),
            ),
            $input
        );

        return CaseConverter::normalize($input);
    }

    private function ignorableRecord(LogRecord $record): bool
    {
        foreach (self::IGNORED_LOG_MESSAGES as $message) {
            if (\str_contains($record->message, $message)) {
                return true;
            }
        }

        return false;
    }
}
