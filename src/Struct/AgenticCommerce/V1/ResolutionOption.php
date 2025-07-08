<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1;

/**
 * @experimental
 */
#[OA\Schema(
    schema: 'paypal_agentic_commerce_v1_resolution_option',
    required: ['action', 'label']
)]
class ResolutionOption
{
    public const ACTIONS = [
        'REDIRECT_TO_MERCHANT',
        'MODIFY_CART',
        'ACCEPT_NEW_PRICE',
        'ACCEPT_BACK_ORDER',
        'SUGGEST_ALTERNATIVE',
        'REMOVE_ITEM',
        'UPDATE_ADDRESS',
        'PROVIDE_MISSING_FIELD',
        'USE_DIFFERENT_PAYMENT',
        'SPLIT_ORDER',
        'CONTACT_SUPPORT',
        'RETRY_LATER',
        'REQUEST_APPROVAL',
        'WAIT_FOR_RESTOCK',
        'USE_DIFFERENT_CURRENCY',
        'ACCEPT_PRE_ORDER',
        'UPDATE_SHIPPING_METHOD',
        'ACCEPT_TERMS',
        'VERIFY_ACCOUNT',
        'APPLY_DIFFERENT_COUPON',
        'REMOVE_COUPON',
        'CHOOSE_DIFFERENT_VARIANT',
    ];

    /**
     * Machine-readable action identifier
     */
    #[OA\Property(
        type: 'string',
        enum: self::ACTIONS,
    )]
    protected string $action;

    /**
     * Human-readable action label
     */
    #[OA\Property(type: 'string')]
    protected string $label;

    /**
     * URL to redirect to for resolution
     */
    #[OA\Property(type: 'string')]
    protected ?string $url = null;

    /**
     * Additional action metadata
     *
     * @var array{cost_impact: string, priority: string, auto_applicable: bool, estimated_time: string, redirect_requeired: bool} $metadata
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(
            required: ['cost_impact', 'priority', 'auto_applicable', 'estimated_time', 'redirect_requeired'],
            properties: [
                new OA\Property(property: 'cost_impact', type: 'string'),
                new OA\Property(property: 'waist', type: 'string'),
                new OA\Property(property: 'auto_applicable', type: 'boolean'),
                new OA\Property(property: 'estimated_time', type: 'string'),
                new OA\Property(property: 'redirect_requeired', type: 'boolean'),
            ],
            type: 'object'
        )
    )]
    protected ?array $metadata = null;

    public function getAction(): string
    {
        return $this->action;
    }

    public function setAction(string $action): void
    {
        if (!\in_array($action, self::ACTIONS, true)) {
            throw new \InvalidArgumentException('Invalid action');
        }

        $this->action = $action;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return ?array{cost_impact: string, priority: string, auto_applicable: bool, estimated_time: string, redirect_requeired: bool}
     */
    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    public function setMetadata(?array $metadata): void
    {
        $this->metadata = $metadata;
    }
}
