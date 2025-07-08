<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Context;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_agentic_commerce_v1_context_business_rule_error_context')]
class BusinessRuleErrorContext extends Struct implements ContextInterface
{
    /**
     * Specific business rule issue type
     *
     * Enum: [ MINIMUM_ORDER_NOT_MET, MINIMUM_QUANTITY_NOT_MET, MAXIMUM_QUANTITY_EXCEEDED, CART_LIMIT_EXCEEDED, CUSTOMER_ACCOUNT_SUSPENDED, PURCHASE_LIMIT_EXCEEDED, BULK_ORDER_APPROVAL_REQUIRED, STORE_TEMPORARILY_CLOSED, AGE_RESTRICTED_PRODUCT, LOYALTY_PROGRAM_VALIDATION_FAILED, BUSINESS_HOURS_RESTRICTION, PRODUCT_ARCHIVED ]
     */
    #[OA\Property(
        type: 'string',
        enum: ['MINIMUM_ORDER_NOT_MET', 'MINIMUM_QUANTITY_NOT_MET', 'MAXIMUM_QUANTITY_EXCEEDED', 'CART_LIMIT_EXCEEDED', 'CUSTOMER_ACCOUNT_SUSPENDED', 'PURCHASE_LIMIT_EXCEEDED', 'BULK_ORDER_APPROVAL_REQUIRED', 'STORE_TEMPORARILY_CLOSED', 'AGE_RESTRICTED_PRODUCT', 'LOYALTY_PROGRAM_VALIDATION_FAILED', 'BUSINESS_HOURS_RESTRICTION', 'PRODUCT_ARCHIVED']
    )]
    protected string $specificIssue;

    /**
     * Current order amount
     */
    #[OA\Property(type: 'string')]
    protected string $currentAmount;

    /**
     * Required minimum amount
     */
    #[OA\Property(type: 'string')]
    protected string $requiredAmount;

    /**
     * Maximum allowed amount
     */
    #[OA\Property(type: 'string')]
    protected string $maximum_Amount;

    /**
     * Amount needed to meet minimum
     */
    #[OA\Property(type: 'string')]
    protected string $remainingAmount;

    /**
     * Customer account status
     */
    #[OA\Property(type: 'string')]
    protected string $accountStatus;

    /**
     * Reason for account suspension
     */
    #[OA\Property(type: 'string')]
    protected string $suspensionReason;

    /**
     * Date of account suspension
     */
    #[OA\Property(type: 'string')]
    protected string $suspensionDate;

    /**
     * Monthly purchase limit
     */
    #[OA\Property(type: 'string')]
    protected string $monthlyLimit;

    /**
     * Current month purchase total
     */
    #[OA\Property(type: 'string')]
    protected string $currentMonthTotal;

    /**
     * When limits reset
     */
    #[OA\Property(type: 'string')]
    protected string $resetDate;

    /**
     * Total quantity in bulk order
     */
    #[OA\Property(type: 'integer')]
    protected int $totalQuantity;

    /**
     * Quantity requiring approval
     */
    #[OA\Property(type: 'integer')]
    protected int $approvalThreshold;

    /**
     * When maintenance ends
     */
    #[OA\Property(type: 'string')]
    protected string $maintenanceEndTime;

    /**
     * Current service status
     */
    #[OA\Property(type: 'string')]
    protected string $serviceStatus;

    /**
     * Seconds before retry recommended
     */
    #[OA\Property(type: 'integer')]
    protected int $retryAfter;

    /**
     * Support contact information
     */
    #[OA\Property(type: 'string')]
    protected string $contactInfo;

    /**
     * Items with restrictions
     *
     * @var string[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(type: 'string'),
    )]
    protected array $restrictedItems;

    /**
     * Required minimum age
     */
    #[OA\Property(type: 'integer')]
    protected int $ageRequirement;

    /**
     * Store business hours
     *
     * @var array{open_time: string, close_time: string, timezone: string}
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(
            required: ['open_time', 'close_time', 'timezone'],
            properties: [
                new OA\Property(property: 'open_time', type: 'string'),
                new OA\Property(property: 'close_time', type: 'string'),
                new OA\Property(property: 'timezone', type: 'string'),
            ],
            type: 'object'
        )
    )]
    protected array $businessHours;

    /**
     * Amount needed to meet minimum requirements
     */
    #[OA\Property(type: 'string')]
    protected string $shortageAmount;

    /**
     * Amount by which limit is exceeded
     */
    #[OA\Property(type: 'string')]
    protected string $exceedsBy;
}
