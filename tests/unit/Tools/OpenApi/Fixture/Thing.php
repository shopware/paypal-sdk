<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Tools\OpenApi\Fixture;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

class Thing extends Struct
{
    protected string $id;

    protected string $status;

    #[OA\Property(type: 'string', maxLength: 127)]
    protected string $name;

    #[OA\Property(type: 'string', enum: ['A', 'B', 'D'])]
    protected string $kind;

    protected string $line1;

    protected string $postCode;

    protected int $count;

    protected string $created;

    protected PartCollection $parts;

    protected string $internal;
}
