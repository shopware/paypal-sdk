<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\MerchantIntegrations;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v1_merchant_integrations_capability')]
class Capability extends Struct
{
    public const string STATUS_ACTIVE = 'ACTIVE';
    public const string STATUS_SUSPENDED = 'SUSPENDED';
    public const string STATUS_REVOKED = 'REVOKED';
    public const string STATUS_APPROVED = 'APPROVED';
    public const string STATUS_NEED_DATA = 'NEED_DATA';
    public const string STATUS_DENY = 'DENY';
    public const string STATUS_IN_REVIEW = 'IN_REVIEW';
    public const string STATUS_INACTIVE = 'INACTIVE';
    public const string STATUS_PENDING = 'PENDING';

    public const array STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_SUSPENDED,
        self::STATUS_REVOKED,
        self::STATUS_APPROVED,
        self::STATUS_NEED_DATA,
        self::STATUS_DENY,
        self::STATUS_IN_REVIEW,
        self::STATUS_INACTIVE,
        self::STATUS_PENDING,
    ];

    #[OA\Property(type: 'string')]
    protected string $name;

    #[OA\Property(type: 'string', enum: self::STATUSES)]
    protected string $status;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}
