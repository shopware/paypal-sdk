<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tools\OpenApi;

/*
 * Checks the structs against PayPal's OpenAPI specs, see README.md.
 *
 *   --kind=a,b         Only list these finding kinds. Default: all but spec-only.
 *   --no-baseline      Ignore the baseline.
 *   --update-baseline  Baseline every current finding, keeping known reasons.
 *   --fix              Write the spec's facets into #[OA\Property], except baselined ones.
 *
 * Exits 1 on a finding that is not baselined, or on a stale baseline or Roots::UNCOVERED entry.
 */

require __DIR__ . '/../../vendor/autoload.php';

$projectDir = \dirname(__DIR__, 2);
$baselineFile = __DIR__ . '/baseline.php';

$options = getopt('', ['kind:', 'no-baseline', 'update-baseline', 'fix']);

$kinds = \is_string($options['kind'] ?? null) ? explode(',', $options['kind']) : Finding::GATING;
if (($unknown = array_diff($kinds, Finding::KINDS)) !== []) {
    fwrite(\STDERR, \sprintf('Unknown kind "%s", expected one of: %s', implode(', ', $unknown), implode(', ', Finding::KINDS)) . \PHP_EOL);

    exit(2);
}

$report = (new StructConformance(
    new SpecRepository($projectDir . '/var/openapi/specs/' . SpecRepository::REF),
    Roots::operations(),
    Roots::UNCOVERED,
    StructConformance::findStructs($projectDir . '/src/Struct', 'Shopware\PayPalSDK\Struct'),
))->run();

$baseline = isset($options['no-baseline']) ? new Baseline() : Baseline::read($baselineFile);

if (isset($options['update-baseline'])) {
    $updated = Baseline::read($baselineFile)->update($report->filter(Finding::GATING));
    $updated->write($baselineFile);
    echo \sprintf('Baselined %d findings in %s; give every new entry a reason.', \count($updated->entries), basename($baselineFile)) . \PHP_EOL;

    exit(0);
}

if (isset($options['fix'])) {
    $count = AttributeWriter::apply($report->fixes($baseline));
    echo \sprintf('Changed %d #[OA\Property] attributes. Run `composer ecs-fix` and re-run the check.', $count) . \PHP_EOL;

    exit(0);
}

$findings = $report->filter($kinds, $baseline);

echo $report->render($findings);
echo $report->summary($findings) . \PHP_EOL;

$stale = [
    ...array_map(static fn (string $id): string => 'baseline.php: ' . $id, $baseline->stale($report->findings)),
    ...array_map(static fn (string $entry): string => 'Roots::UNCOVERED: ' . $entry, $report->staleUncovered),
];
if ($stale !== []) {
    echo \PHP_EOL . 'Entries that match nothing anymore, remove them:' . \PHP_EOL . '  ' . implode(\PHP_EOL . '  ', $stale) . \PHP_EOL;
}

$failing = array_filter($findings, static fn (Finding $f): bool => \in_array($f->kind, Finding::GATING, true));

exit($failing === [] && $stale === [] ? 0 : 1);
