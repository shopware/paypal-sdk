<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Struct\Error;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Struct\Collection;
use Shopware\PayPalSDK\Struct\Error\DetailCollection;

/**
 * @internal
 */
#[CoversClass(Collection::class)]
class DetailCollectionTest extends TestCase
{
    protected DetailCollection $collection;

    protected function setUp(): void
    {
        $this->collection = DetailCollection::createFromAssociative([
            [
                'issue' => 'INVALID_FORMAT',
                'description' => 'Invalid format',
                'field' => 'address',
                'value' => '1234',
            ],
            [
                'description' => 'INVALID_SYNTAX',
                'field' => 'code',
                'value' => 'äöü',
            ],
            [
                'issue' => 'INVALID_SOMETHING',
                'description' => 'Another error',
            ],
        ]);
    }

    public function testGetIssues(): void
    {
        $issues = $this->collection->getIssues();

        static::assertCount(2, $issues);
        static::assertContains('INVALID_FORMAT', $issues);
        static::assertContains('INVALID_SOMETHING', $issues);
    }

    public function testToString(): void
    {
        static::assertSame(
            '[INVALID_FORMAT] (address: 1234) Invalid format | (code: äöü) INVALID_SYNTAX | [INVALID_SOMETHING] Another error',
            (string) $this->collection
        );
    }
}
