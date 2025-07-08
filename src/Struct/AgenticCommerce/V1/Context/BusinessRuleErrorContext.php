<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Context;

use OpenApi\Attributes as OA;

/**
 * @experimental
 */
#[OA\Schema(schema: 'paypal_agentic_commerce_v1_context_business_rule_error_context')]
class BusinessRuleErrorContext extends AbstractContext
{
    public const SPECIFIC_ISSUES = [
        'MINIMUM_ORDER_NOT_MET' => 'MINIMUM_ORDER_NOT_MET',
        'MINIMUM_QUANTITY_NOT_MET' => 'MINIMUM_QUANTITY_NOT_MET',
        'MAXIMUM_QUANTITY_EXCEEDED' => 'MAXIMUM_QUANTITY_EXCEEDED',
        'CART_LIMIT_EXCEEDED' => 'CART_LIMIT_EXCEEDED',
        'CUSTOMER_ACCOUNT_SUSPENDED' => 'CUSTOMER_ACCOUNT_SUSPENDED',
        'PURCHASE_LIMIT_EXCEEDED' => 'PURCHASE_LIMIT_EXCEEDED',
        'BULK_ORDER_APPROVAL_REQUIRED' => 'BULK_ORDER_APPROVAL_REQUIRED',
        'STORE_TEMPORARILY_CLOSED' => 'STORE_TEMPORARILY_CLOSED',
        'AGE_RESTRICTED_PRODUCT' => 'AGE_RESTRICTED_PRODUCT',
        'LOYALTY_PROGRAM_VALIDATION_FAILED' => 'LOYALTY_PROGRAM_VALIDATION_FAILED',
        'BUSINESS_HOURS_RESTRICTION' => 'BUSINESS_HOURS_RESTRICTION',
        'PRODUCT_ARCHIVED' => 'PRODUCT_ARCHIVED',
    ];

    /**
     * Current order amount
     */
    #[OA\Property(type: 'string')]
    protected ?string $currentAmount = null;

    /**
     * Required minimum amount
     */
    #[OA\Property(type: 'string')]
    protected ?string $requiredAmount = null;

    /**
     * Maximum allowed amount
     */
    #[OA\Property(type: 'string')]
    protected ?string $maximum_Amount = null;

    /**
     * Amount needed to meet minimum
     */
    #[OA\Property(type: 'string')]
    protected ?string $remainingAmount = null;

    /**
     * Customer account status
     */
    #[OA\Property(type: 'string')]
    protected ?string $accountStatus = null;

    /**
     * Reason for account suspension
     */
    #[OA\Property(type: 'string')]
    protected ?string $suspensionReason = null;

    /**
     * Date of account suspension
     */
    #[OA\Property(type: 'string')]
    protected ?string $suspensionDate = null;

    /**
     * Monthly purchase limit
     */
    #[OA\Property(type: 'string')]
    protected ?string $monthlyLimit = null;

    /**
     * Current month purchase total
     */
    #[OA\Property(type: 'string')]
    protected ?string $currentMonthTotal = null;

    /**
     * When limits reset
     */
    #[OA\Property(type: 'string')]
    protected ?string $resetDate = null;

    /**
     * Total quantity in bulk order
     */
    #[OA\Property(type: 'integer')]
    protected ?int $totalQuantity = null;

    /**
     * Quantity requiring approval
     */
    #[OA\Property(type: 'integer')]
    protected ?int $approvalThreshold = null;

    /**
     * When maintenance ends
     */
    #[OA\Property(type: 'string')]
    protected ?string $maintenanceEndTime = null;

    /**
     * Current service status
     */
    #[OA\Property(type: 'string')]
    protected ?string $serviceStatus = null;

    /**
     * Seconds before retry recommended
     */
    #[OA\Property(type: 'integer')]
    protected ?int $retryAfter = null;

    /**
     * Support contact information
     */
    #[OA\Property(type: 'string')]
    protected ?string $contactInfo = null;

    /**
     * Items with restrictions
     *
     * @var string[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(type: 'string'),
    )]
    protected ?array $restrictedItems = null;

    /**
     * Required minimum age
     */
    #[OA\Property(type: 'integer')]
    protected ?int $ageRequirement = null;

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
    protected ?array $businessHours = null;

    /**
     * Amount needed to meet minimum requirements
     */
    #[OA\Property(type: 'string')]
    protected ?string $shortageAmount = null;

    /**
     * Amount by which limit is exceeded
     */
    #[OA\Property(type: 'string')]
    protected ?string $exceedsBy = null;

    public function getCurrentAmount(): ?string
    {
        return $this->currentAmount;
    }

    public function setCurrentAmount(?string $currentAmount): void
    {
        $this->currentAmount = $currentAmount;
    }

    public function getRequiredAmount(): ?string
    {
        return $this->requiredAmount;
    }

    public function setRequiredAmount(?string $requiredAmount): void
    {
        $this->requiredAmount = $requiredAmount;
    }

    public function getMaximumAmount(): ?string
    {
        return $this->maximum_Amount;
    }

    public function setMaximumAmount(?string $maximum_Amount): void
    {
        $this->maximum_Amount = $maximum_Amount;
    }

    public function getRemainingAmount(): ?string
    {
        return $this->remainingAmount;
    }

    public function setRemainingAmount(?string $remainingAmount): void
    {
        $this->remainingAmount = $remainingAmount;
    }

    public function getAccountStatus(): ?string
    {
        return $this->accountStatus;
    }

    public function setAccountStatus(?string $accountStatus): void
    {
        $this->accountStatus = $accountStatus;
    }

    public function getSuspensionReason(): ?string
    {
        return $this->suspensionReason;
    }

    public function setSuspensionReason(?string $suspensionReason): void
    {
        $this->suspensionReason = $suspensionReason;
    }

    public function getSuspensionDate(): ?string
    {
        return $this->suspensionDate;
    }

    public function setSuspensionDate(?string $suspensionDate): void
    {
        $this->suspensionDate = $suspensionDate;
    }

    public function getMonthlyLimit(): ?string
    {
        return $this->monthlyLimit;
    }

    public function setMonthlyLimit(?string $monthlyLimit): void
    {
        $this->monthlyLimit = $monthlyLimit;
    }

    public function getCurrentMonthTotal(): ?string
    {
        return $this->currentMonthTotal;
    }

    public function setCurrentMonthTotal(?string $currentMonthTotal): void
    {
        $this->currentMonthTotal = $currentMonthTotal;
    }

    public function getResetDate(): ?string
    {
        return $this->resetDate;
    }

    public function setResetDate(?string $resetDate): void
    {
        $this->resetDate = $resetDate;
    }

    public function getTotalQuantity(): ?int
    {
        return $this->totalQuantity;
    }

    public function setTotalQuantity(?int $totalQuantity): void
    {
        $this->totalQuantity = $totalQuantity;
    }

    public function getApprovalThreshold(): ?int
    {
        return $this->approvalThreshold;
    }

    public function setApprovalThreshold(?int $approvalThreshold): void
    {
        $this->approvalThreshold = $approvalThreshold;
    }

    public function getMaintenanceEndTime(): ?string
    {
        return $this->maintenanceEndTime;
    }

    public function setMaintenanceEndTime(?string $maintenanceEndTime): void
    {
        $this->maintenanceEndTime = $maintenanceEndTime;
    }

    public function getServiceStatus(): ?string
    {
        return $this->serviceStatus;
    }

    public function setServiceStatus(?string $serviceStatus): void
    {
        $this->serviceStatus = $serviceStatus;
    }

    public function getRetryAfter(): ?int
    {
        return $this->retryAfter;
    }

    public function setRetryAfter(?int $retryAfter): void
    {
        $this->retryAfter = $retryAfter;
    }

    public function getContactInfo(): ?string
    {
        return $this->contactInfo;
    }

    public function setContactInfo(?string $contactInfo): void
    {
        $this->contactInfo = $contactInfo;
    }

    /**
     * @return ?string[]
     */
    public function getRestrictedItems(): ?array
    {
        return $this->restrictedItems;
    }

    /**
     * @param ?string[] $restrictedItems
     */
    public function setRestrictedItems(?array $restrictedItems): void
    {
        $this->restrictedItems = $restrictedItems;
    }

    public function addRestrictedItem(string $restrictedItem): void
    {
        $this->restrictedItems[] = $restrictedItem;
    }

    public function getAgeRequirement(): ?int
    {
        return $this->ageRequirement;
    }

    public function setAgeRequirement(?int $ageRequirement): void
    {
        $this->ageRequirement = $ageRequirement;
    }

    /**
     * @return ?array{open_time: string, close_time: string, timezone: string}
     */
    public function getBusinessHours(): ?array
    {
        return $this->businessHours;
    }

    public function setBusinessHours(string $openTime, string $closeTime, string $timezone): void
    {
        $this->businessHours = [
            'open_time' => $openTime,
            'close_time' => $closeTime,
            'timezone' => $timezone,
        ];
    }

    public function resetBusinessHours(): void
    {
        $this->businessHours = null;
    }

    public function getShortageAmount(): ?string
    {
        return $this->shortageAmount;
    }

    public function setShortageAmount(?string $shortageAmount): void
    {
        $this->shortageAmount = $shortageAmount;
    }

    public function getExceedsBy(): ?string
    {
        return $this->exceedsBy;
    }

    public function setExceedsBy(?string $exceedsBy): void
    {
        $this->exceedsBy = $exceedsBy;
    }
}
