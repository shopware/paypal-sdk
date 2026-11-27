<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Struct;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Struct\ArrayStruct;
use Shopware\PayPalSDK\Struct\Struct;

/**
 * @internal
 */
#[CoversClass(ArrayStruct::class)]
#[CoversClass(Struct::class)]
class ArrayStructTest extends TestCase
{
    public function testJsonSerialize(): void
    {
        $data = ['id' => 'ORDER-1', 'purchase_units' => [['reference_id' => 'default']]];

        static::assertSame($data, (new ArrayStruct())->assign($data)->jsonSerialize());
    }
}
