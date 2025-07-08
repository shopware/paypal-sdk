<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

/**
 * @experimental
 */
#[OA\Schema(
    schema: 'paypal_agentic_commerce_v1_link',
    required: ['rel', 'href']
)]
class Link extends Struct
{
    public const REL__SELF = 'rel';
    public const REL__UPDATE = 'update';
    public const REL__CHECKOUT = 'checkout';

    /**
     * Link relationship type
     *
     * Enum: [ self, update, checkout ]
     */
    #[OA\Property(
        type: 'string',
        enum: [self::REL__SELF, self::REL__UPDATE, self::REL__CHECKOUT]
    )]
    protected string $rel;

    /**
     * Target URL for the link
     *
     * example: https://your-domain.com/api/paypal/v1/merchant-cart/CART-123
     */
    #[OA\Property(type: 'string')]
    protected string $href;

    /**
     * HTTP method for the link
     */
    #[OA\Property(
        type: 'string',
        enum: ['GET', 'POST', 'PUT']
    )]
    protected ?string $method = null;

    /**
     * Human-readable description of the link
     */
    #[OA\Property(type: 'string')]
    protected ?string $title = null;

    /**
     * Expected content type
     */
    #[OA\Property(type: 'string')]
    protected ?string $type = null;

    public function getRel(): string
    {
        return $this->rel;
    }

    public function setRel(string $rel): void
    {
        if (!\in_array($rel, [self::REL__SELF, self::REL__UPDATE, self::REL__CHECKOUT], true)) {
            throw new \InvalidArgumentException('Invalid rel option');
        }

        $this->rel = $rel;
    }

    public function getHref(): string
    {
        return $this->href;
    }

    public function setHref(string $href): void
    {
        $this->href = $href;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(?string $method): void
    {
        if (!\in_array($method, ['GET', 'POST', 'PUT'], true)) {
            throw new \InvalidArgumentException('Invalid method');
        }

        $this->method = $method;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }
}
