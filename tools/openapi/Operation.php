<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tools\OpenApi;

use Shopware\PayPalSDK\Struct\Struct;

/**
 * An API operation the SDK calls, with the struct it sends and the one it
 * receives. For a body that is a JSON array, the struct is the element.
 */
final readonly class Operation implements \Stringable
{
    /**
     * @param string $spec spec file name, e.g. `checkout_orders_v2`
     * @param class-string<Struct>|null $sends
     * @param class-string<Struct>|null $receives
     */
    public function __construct(
        public string $spec,
        public string $method,
        public string $path,
        public ?string $sends = null,
        public ?string $receives = null,
    ) {}

    public function __toString(): string
    {
        return \sprintf('%s %s %s', $this->spec, $this->method, $this->path);
    }
}
