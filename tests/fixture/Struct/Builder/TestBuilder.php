<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Fixture\Struct\Builder;

use Shopware\PayPalSDK\Struct\Struct;

/**
 * @internal
 */
class TestBuilder
{
    /** @var array<string, mixed> */
    private array $data = [];

    /**
     * @return array<string, mixed>
     */
    public function build(): array
    {
        return $this->data;
    }

    public function withSomething(Struct $struct): void
    {
        $this->data = array_merge($this->data, $struct->jsonSerialize());
    }
}
