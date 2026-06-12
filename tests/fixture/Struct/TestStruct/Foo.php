<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Fixture\Struct\TestStruct;

use Shopware\PayPalSDK\Struct\Struct;

/**
 * @internal
 */
class Foo extends Struct
{
    protected string $fooBaz;

    protected Bar $bar;

    protected function setFooBaz(string $fooBaz): void
    {
        $this->fooBaz = $fooBaz;
    }

    protected function setBar(Bar $bar): void
    {
        $this->bar = $bar;
    }
}
