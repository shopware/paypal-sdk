<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Tools\OpenApi;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Tools\OpenApi\Spec;

/**
 * @internal
 */
#[CoversClass(Spec::class)]
class SpecTest extends TestCase
{
    public function testFlattenMergesAllOfMembers(): void
    {
        $thing = self::spec()->flatten(['$ref' => '#/components/schemas/thing']);

        static::assertSame('object', $thing['type']);
        static::assertSame(['id', 'status', 'name', 'created', 'kind', 'parts', 'extra'], array_keys((array) $thing['properties']));
    }

    public function testFlattenUnwrapsAnAllOfAroundAScalar(): void
    {
        $created = self::spec()->flatten(['allOf' => [['$ref' => '#/components/schemas/date_time'], ['description' => 'When.']]]);

        static::assertSame(['type' => 'string', 'format' => 'ppaas_date_time_v3', 'minLength' => 20], $created);
    }

    public function testFlattenStopsAtCycles(): void
    {
        $loop = self::spec()->flatten(['$ref' => '#/components/schemas/loop']);

        static::assertSame(['a'], array_keys((array) $loop['properties']));
    }

    public function testSchemaNameFollowsAliases(): void
    {
        $spec = self::spec();

        static::assertSame('part', $spec->schemaName(['$ref' => '#/components/schemas/part_alias']));
        static::assertSame('part', $spec->schemaName(['allOf' => [['$ref' => '#/components/schemas/part'], ['description' => 'A part.']]]));
        static::assertNull($spec->schemaName(['type' => 'object', 'properties' => []]));
    }

    public function testOperationMatchesPathParametersByPosition(): void
    {
        $operation = self::spec()->operation('POST', '/v1/things');

        static::assertNotNull($operation);
        static::assertSame(['$ref' => '#/components/schemas/thing_request'], $operation['request']);
        // only 2xx bodies
        static::assertSame([['$ref' => '#/components/schemas/thing']], $operation['responses']);
        static::assertSame(['201 full', '201 minimal'], array_keys($operation['examples']));
        static::assertNull(self::spec()->operation('GET', '/v1/things'));
    }

    private static function spec(): Spec
    {
        return Spec::parse('fixture_v1', (string) file_get_contents(__DIR__ . '/Fixture/fixture_v1.json'));
    }
}
