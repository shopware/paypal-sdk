<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Util;

use Psr\SimpleCache\CacheInterface;
use Shopware\PayPalSDK\Struct\V1\Token;

final class TokenArrayCache implements CacheInterface
{
    /**
     * @param array<string, Token> $tokens
     */
    public function __construct(
        protected array $tokens = [],
    ) {}

    /**
     * @param null $default
     *
     * @return Token|null
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->tokens[$key] ?? $default;
    }

    /**
     * @param Token $value
     *
     * @return true
     */
    public function set(string $key, mixed $value, int|\DateInterval|null $ttl = null): bool
    {
        $this->tokens[$key] = $value;

        return true;
    }

    /**
     * @return true
     */
    public function delete(string $key): bool
    {
        unset($this->tokens[$key]);

        return true;
    }

    /**
     * @return true
     */
    public function clear(): bool
    {
        $this->tokens = [];

        return true;
    }

    /**
     * @param null $default
     *
     * @return iterable<string, ?Token>
     */
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        foreach ($keys as $key) {
            yield $key => $this->get($key, $default);
        }
    }

    /**
     * @param iterable<string, Token> $values
     *
     * @return true
     */
    public function setMultiple(iterable $values, int|\DateInterval|null $ttl = null): bool
    {
        foreach ($values as $key => $token) {
            $this->set($key, $token, $ttl);
        }

        return true;
    }

    /**
     * @return true
     */
    public function deleteMultiple(iterable $keys): bool
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }

        return true;
    }

    public function has(string $key): bool
    {
        return isset($this->tokens[$key]);
    }
}
