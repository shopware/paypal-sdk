<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tools\OpenApi;

/**
 * Findings that are deliberate, by {@see Finding::id()}, with the reason.
 */
final readonly class Baseline
{
    public const string TODO = 'TODO: why is this deliberate?';

    private const string HEADER = <<<'PHP'
        <?php declare(strict_types=1);
        /*
         * (c) shopware AG <info@shopware.com>
         * For the full copyright and license information, please view the LICENSE
         * file that was distributed with this source code.
         */

        // finding id => why the deviation is deliberate, see tools/openapi/README.md
        return [

        PHP;

    /**
     * @param array<string, string> $entries
     */
    public function __construct(public array $entries = []) {}

    public static function read(string $file): self
    {
        if (!is_file($file)) {
            return new self();
        }

        $entries = require $file;
        if (!\is_array($entries)) {
            throw new \RuntimeException($file . ' must return an array');
        }

        $valid = [];
        foreach ($entries as $id => $reason) {
            if (!\is_string($id) || !\is_string($reason)) {
                throw new \RuntimeException($file . ' must map finding ids to reasons');
            }
            $valid[$id] = $reason;
        }

        return new self($valid);
    }

    public function write(string $file): void
    {
        $entries = $this->entries;
        ksort($entries);

        $php = self::HEADER;
        foreach ($entries as $id => $reason) {
            $php .= \sprintf('    %s => %s,', self::quote($id), self::quote($reason)) . \PHP_EOL;
        }

        file_put_contents($file, $php . '];' . \PHP_EOL);
    }

    /**
     * Single-quoted, with class names readable: a backslash needs escaping only
     * before a quote, another backslash or the end.
     */
    private static function quote(string $value): string
    {
        return '\'' . preg_replace('/\\\\(?=[\'\\\\]|$)|\'/', '\\\\$0', $value) . '\'';
    }

    public function has(Finding $finding): bool
    {
        return isset($this->entries[$finding->id()]);
    }

    /**
     * The given findings as a baseline, keeping the reasons already given.
     *
     * @param list<Finding> $findings
     */
    public function update(array $findings): self
    {
        $entries = [];
        foreach ($findings as $finding) {
            $entries[$finding->id()] = $this->entries[$finding->id()] ?? self::TODO;
        }

        return new self($entries);
    }

    /**
     * Entries no finding matches anymore.
     *
     * @param list<Finding> $findings
     *
     * @return list<string>
     */
    public function stale(array $findings): array
    {
        $ids = array_flip(array_map(static fn (Finding $f): string => $f->id(), $findings));

        return array_values(array_filter(array_keys($this->entries), static fn (string $id): bool => !isset($ids[$id])));
    }
}
