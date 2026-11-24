<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tools\OpenApi;

/**
 * Loads PayPal's OpenAPI specs at commit {@see self::REF}. Downloads are
 * cached in `$dir`, which must be specific to that commit.
 */
final class SpecRepository
{
    public const string REPOSITORY = 'paypal/paypal-rest-api-specifications';

    public const string REF = '90e8041ffe02d80c452d2b476bedd59a8d219bdc';

    /** @var array<string, Spec> */
    private array $loaded = [];

    public function __construct(private readonly string $dir) {}

    public function get(string $name): Spec
    {
        return $this->loaded[$name] ??= $this->load($name);
    }

    private function load(string $name): Spec
    {
        $file = $this->dir . '/' . $name . '.json';
        if (is_file($file)) {
            return Spec::parse($name, (string) file_get_contents($file));
        }

        $url = \sprintf('https://raw.githubusercontent.com/%s/%s/openapi/%s.json', self::REPOSITORY, self::REF, $name);
        $json = @file_get_contents($url);
        if ($json === false) {
            throw new \RuntimeException(\sprintf('Cannot download %s: %s', $url, error_get_last()['message'] ?? 'unknown error'));
        }

        $spec = Spec::parse($name, $json);

        if (!is_dir($this->dir) && !mkdir($this->dir, 0o775, true) && !is_dir($this->dir)) {
            throw new \RuntimeException('Cannot create ' . $this->dir);
        }

        // rename() is atomic, so an interrupted run leaves no partial file
        $tmp = $file . '.' . getmypid();
        file_put_contents($tmp, $json);
        rename($tmp, $file);

        return $spec;
    }
}
