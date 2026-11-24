<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Tools\OpenApi;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Tests\Unit\Tools\OpenApi\Fixture\Part;
use Shopware\PayPalSDK\Tests\Unit\Tools\OpenApi\Fixture\Thing;
use Shopware\PayPalSDK\Tests\Unit\Tools\OpenApi\Fixture\Uncovered;
use Shopware\PayPalSDK\Tools\OpenApi\Baseline;
use Shopware\PayPalSDK\Tools\OpenApi\Finding;
use Shopware\PayPalSDK\Tools\OpenApi\Operation;
use Shopware\PayPalSDK\Tools\OpenApi\Pairing;
use Shopware\PayPalSDK\Tools\OpenApi\Report;
use Shopware\PayPalSDK\Tools\OpenApi\SpecRepository;
use Shopware\PayPalSDK\Tools\OpenApi\StructConformance;

/**
 * @internal
 */
#[CoversClass(StructConformance::class)]
#[CoversClass(Pairing::class)]
#[CoversClass(Report::class)]
class StructConformanceTest extends TestCase
{
    private const string FIXTURE = __DIR__ . '/Fixture';

    public function testPairsNestedStructsWithTheSchemaOfTheirField(): void
    {
        $report = self::report();

        static::assertSame(['thing_request (req)', 'thing'], array_map(strval(...), $report->pairings[Thing::class]));
        // `part_alias` is labelled by the schema it refers to
        static::assertSame(['part (req)', 'part'], array_map(strval(...), $report->pairings[Part::class]));
    }

    public function testFindings(): void
    {
        static::assertEqualsCanonicalizing([
            'attribute Thing::$name maxLength 127 -> 3000',
            'attribute Thing::$name +minLength 1',
            // the SDK is held to the union of `thing_request` and `thing`
            'enum Thing::$kind -D',
            'enum Thing::$kind +C',
            // `jsonSerialize()` writes `line_1`
            'field Thing::$line1',
            // `assign()` maps `postcode` to `$postcode`
            'field Thing::$postCode',
            'spec-only Thing::$postcode',
            'type Thing::$count',
            'sdk-only Thing::$internal',
            'spec-only Thing::$extra',
            // the full example omits `parts[].note`
            'getter Part::$note',
            'unpaired Orphan',
        ], self::ids(self::report()->findings));
    }

    public function testFixesSkipBaselinedFacets(): void
    {
        $report = self::report();
        $file = (string) (new \ReflectionClass(Thing::class))->getFileName();

        static::assertSame([$file => [Thing::class => ['name' => ['minLength' => 1, 'maxLength' => 3000]]]], $report->fixes(new Baseline()));

        $baseline = new Baseline(['attribute ' . Thing::class . '::$name maxLength 127 -> 3000' => 'stricter on purpose']);
        static::assertSame([$file => [Thing::class => ['name' => ['minLength' => 1]]]], $report->fixes($baseline));
    }

    public function testReportsUncoveredEntriesThatMatchNothing(): void
    {
        $report = self::report([Uncovered::class => 'covered elsewhere', 'Gone' => 'removed']);

        static::assertSame(['Gone'], $report->staleUncovered);
    }

    public function testRunsRepeatedly(): void
    {
        $conformance = self::conformance();

        static::assertEquals($conformance->run(), $conformance->run());
    }

    public function testFailsOnAnOperationTheSpecLacks(): void
    {
        $conformance = new StructConformance(
            new SpecRepository(self::FIXTURE),
            [new Operation('fixture_v1', 'GET', '/v1/things', receives: Thing::class)],
            [],
            [],
        );

        $this->expectExceptionMessage('fixture_v1 GET /v1/things is not in the spec');
        $conformance->run();
    }

    /**
     * @param array<string, string> $uncovered
     */
    private static function report(array $uncovered = [Uncovered::class => 'not paired on purpose']): Report
    {
        return self::conformance($uncovered)->run();
    }

    /**
     * @param array<string, string> $uncovered
     */
    private static function conformance(array $uncovered = []): StructConformance
    {
        return new StructConformance(
            new SpecRepository(self::FIXTURE),
            [new Operation('fixture_v1', 'POST', '/v1/things', sends: Thing::class, receives: Thing::class)],
            $uncovered,
            StructConformance::findStructs(self::FIXTURE, __NAMESPACE__ . '\Fixture'),
        );
    }

    /**
     * @param list<Finding> $findings
     *
     * @return list<string>
     */
    private static function ids(array $findings): array
    {
        return array_map(static fn (Finding $f): string => str_replace(__NAMESPACE__ . '\Fixture\\', '', $f->id()), $findings);
    }
}
