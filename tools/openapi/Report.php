<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tools\OpenApi;

use Shopware\PayPalSDK\Struct\Struct;

final readonly class Report
{
    /**
     * @param list<Finding> $findings
     * @param array<class-string<Struct>, list<Pairing>> $pairings
     * @param list<string> $staleUncovered {@see Roots::UNCOVERED} entries that match no unreached struct
     */
    public function __construct(
        public array $findings,
        public array $pairings,
        public int $structCount,
        public array $staleUncovered,
    ) {}

    /**
     * @param list<string> $kinds
     *
     * @return list<Finding>
     */
    public function filter(array $kinds, Baseline $baseline = new Baseline()): array
    {
        return array_values(array_filter(
            $this->findings,
            static fn (Finding $f): bool => \in_array($f->kind, $kinds, true) && !$baseline->has($f),
        ));
    }

    /**
     * The facets `--fix` writes: those of attribute findings not in the baseline.
     *
     * @return array<string, array<string, array<string, array<string, int|float|string|null>>>> file => class => property => facet => value, null removes it
     */
    public function fixes(Baseline $baseline): array
    {
        $fixes = [];
        foreach ($this->filter([Finding::ATTRIBUTE], $baseline) as $finding) {
            $file = (string) (new \ReflectionClass($finding->class))->getFileName();
            $fixes[$file][$finding->class][$finding->property] = [...$fixes[$file][$finding->class][$finding->property] ?? [], ...$finding->fix];
        }

        return $fixes;
    }

    /**
     * @param list<Finding> $findings
     */
    public function render(array $findings): string
    {
        $byClass = [];
        foreach ($findings as $finding) {
            $byClass[$finding->class][] = $finding;
        }
        ksort($byClass);

        $out = '';
        foreach ($byClass as $class => $classFindings) {
            $out .= Finding::shortClass($class) . \PHP_EOL;
            if (isset($this->pairings[$class])) {
                $out .= '  ~ ' . implode(', ', array_unique(array_map(strval(...), $this->pairings[$class]))) . \PHP_EOL;
            }

            usort($classFindings, static fn (Finding $a, Finding $b): int => [$a->kind, $a->property] <=> [$b->kind, $b->property]);

            $lines = array_map(
                static fn (Finding $f): string => \sprintf('  %-10s %s', $f->kind, ($f->property === '' ? '' : '$' . $f->property . ': ') . $f->message),
                $classFindings,
            );
            $out .= implode(\PHP_EOL, array_unique($lines)) . \PHP_EOL . \PHP_EOL;
        }

        return $out;
    }

    /**
     * @param list<Finding> $findings
     */
    public function summary(array $findings): string
    {
        $counts = [];
        foreach ($findings as $finding) {
            $counts[$finding->kind][$finding->id()] = true;
        }

        $parts = [];
        foreach ($counts as $kind => $ids) {
            $parts[] = \count($ids) . ' ' . $kind;
        }

        return \sprintf(
            '%d of %d structs paired with a schema; %s.',
            \count($this->pairings),
            $this->structCount,
            $parts === [] ? 'no findings' : implode(', ', $parts),
        );
    }
}
