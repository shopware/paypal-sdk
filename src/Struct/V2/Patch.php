<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v2_patch')]
class Patch extends Struct
{
    public const OPERATION_ADD = 'add';
    public const OPERATION_REPLACE = 'replace';
    public const OPERATION_REMOVE = 'remove';

    #[OA\Property(type: 'string')]
    protected string $op;

    #[OA\Property(type: 'string')]
    protected string $path;

    /** @var int|float|string|bool|mixed[]|null */
    #[OA\Property(nullable: true, oneOf: [
        new OA\Schema(type: 'integer'),
        new OA\Schema(type: 'float'),
        new OA\Schema(type: 'string'),
        new OA\Schema(type: 'boolean'),
        new OA\Schema(type: 'array', items: new OA\Items(type: 'mixed')),
    ])]
    protected int|float|string|bool|array|null $value = null;

    #[OA\Property(type: 'string')]
    protected string $from;

    public function getOp(): string
    {
        return $this->op;
    }

    public function setOp(string $op): void
    {
        $this->op = $op;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * @return float|int|bool|array<mixed>|string|null
     */
    public function getValue(): float|int|bool|array|string|null
    {
        return $this->value;
    }

    /**
     * @param float|int|bool|array<mixed>|string|null $value
     */
    public function setValue(float|int|bool|array|string|null $value): void
    {
        $this->value = $value;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function setFrom(string $from): void
    {
        $this->from = $from;
    }
}
