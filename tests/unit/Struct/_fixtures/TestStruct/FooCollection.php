<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Struct\_fixtures\TestStruct;

use Shopware\PayPalSDK\Struct\Collection;

/**
 * @internal
 *
 * @extends Collection<Foo>
 */
class FooCollection extends Collection
{
    public static function getExpectedClass(): string
    {
        return Foo::class;
    }
}
