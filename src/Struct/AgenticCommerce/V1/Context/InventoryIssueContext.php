<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Context;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_agentic_commerce_v1_context_inventory_issue_context')]
class InventoryIssueContext extends Struct implements ContextInterface
{
    /**
     * Specific inventory issue type
     *
     * Enum: [ ITEM_OUT_OF_STOCK, INSUFFICIENT_INVENTORY, BACK_ORDERED, PRE_ORDER_ONLY, ITEM_DISCONTINUED, LOW_STOCK_WARNING, INVENTORY_RESERVED, SEASONAL_UNAVAILABLE, VARIANT_NOT_AVAILABLE, CUSTOM_OPTION_UNAVAILABLE ]
     */
    #[OA\Property(
        type: 'string',
        enum: ['ITEM_OUT_OF_STOCK', 'INSUFFICIENT_INVENTORY', 'BACK_ORDERED', 'PRE_ORDER_ONLY', 'ITEM_DISCONTINUED', 'LOW_STOCK_WARNING', 'INVENTORY_RESERVED', 'SEASONAL_UNAVAILABLE', 'VARIANT_NOT_AVAILABLE', 'CUSTOM_OPTION_UNAVAILABLE']
    )]
    protected string $specificIssue;

    /**
     * Product item identifier
     */
    #[OA\Property(type: 'string')]
    protected string $itemId;

    /**
     * Product variant identifier if applicable
     */
    #[OA\Property(type: 'string')]
    protected string $variantId;

    /**
     * Currently available quantity
     */
    #[OA\Property(
        type: 'integer',
        minimum: 0,
    )]
    protected int $availableQuantity;

    #[OA\Property(
        type: 'integer',
        minimum: 1,
    )]
    protected int $requestedQuantity;

    /**
     * Quantity reserved for other transactions
     */
    #[OA\Property(
        type: 'integer',
        minimum: 0,
    )]
    protected int $reservedQuantity;

    /**
     * Expected restock date
     */
    #[OA\Property(type: 'string')]
    protected string $restockDate;

    /**
     * Estimated shipping date for back-orders
     */
    #[OA\Property(type: 'string')]
    protected string $estimatedShipDate;

    /**
     * Maximum allowed back-order quantity
     */
    #[OA\Property(type: 'integer')]
    protected int $backOrderLimit;

    #[OA\Property(type: 'integer')]
    protected int $currentBackOrders;

    #[OA\Property(type: 'string')]
    protected string $discontinuationDate;

    /**
     * Alternative product IDs
     *
     * @var string[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(type: 'string'),
    )]
    protected array $suggestedAlternatives;

    /**
     * Whether newer version is available
     */
    #[OA\Property(type: 'boolean')]
    protected bool $upgradeAvailable;

    /**
     * When seasonal product becomes available
     */
    #[OA\Property(type: 'string')]
    protected string $seasonalStartDate;

    /**
     * When item was last sold
     */
    #[OA\Property(type: 'string')]
    protected string $lastSold;
}
