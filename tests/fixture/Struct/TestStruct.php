<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Fixture\Struct;

use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Tests\Fixture\Struct\TestStruct\Bar;
use Shopware\PayPalSDK\Tests\Fixture\Struct\TestStruct\FooCollection;

/**
 * @internal
 */
class TestStruct extends Struct
{
    protected string $id;

    protected Bar $bar;

    protected FooCollection $foo;

    protected object $notExistingClass;

    /** @var mixed[] */
    protected array $notExistingCollectionClass;

    /** @var string[] */
    protected array $scalarArray;

    protected function setId(string $id): void
    {
        $this->id = $id;
    }

    protected function setBar(Bar $bar): void
    {
        $this->bar = $bar;
    }

    protected function setFoo(FooCollection $foo): void
    {
        $this->foo = $foo;
    }

    protected function setNotExistingClass(object $notExistingClass): void
    {
        $this->notExistingClass = $notExistingClass;
    }

    /**
     * @param mixed[] $notExistingCollectionClass
     */
    protected function setNotExistingCollectionClass(array $notExistingCollectionClass): void
    {
        $this->notExistingCollectionClass = $notExistingCollectionClass;
    }

    /**
     * @param string[] $scalarArray
     */
    protected function setScalarArray(array $scalarArray): void
    {
        $this->scalarArray = $scalarArray;
    }
}
