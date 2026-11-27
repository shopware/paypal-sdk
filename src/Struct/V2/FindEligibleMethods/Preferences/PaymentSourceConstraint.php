<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\FindEligibleMethods\Preferences;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

/**
 * @experimental
 */
#[OA\Schema(
    schema: 'paypal_v2_find_eligible_methods_preferences_payment_source_constraint',
    required: ['constraintType', 'paymentSources']
)]
class PaymentSourceConstraint extends Struct
{
    public const string CONSTRAINT_TYPE_INCLUDE = 'INCLUDE';
    public const string CONSTRAINT_TYPE_EXCLUDE = 'EXCLUDE';

    #[OA\Property(type: 'string', enum: [self::CONSTRAINT_TYPE_INCLUDE, self::CONSTRAINT_TYPE_EXCLUDE])]
    protected string $constraintType;

    /** @var list<string> */
    #[OA\Property(type: 'array', items: new OA\Items(type: 'string'), minItems: 1, maxItems: 75)]
    protected array $paymentSources;

    public function getConstraintType(): string
    {
        return $this->constraintType;
    }

    public function setConstraintType(string $constraintType): void
    {
        $this->constraintType = $constraintType;
    }

    /**
     * @return list<string>
     */
    public function getPaymentSources(): array
    {
        return $this->paymentSources;
    }

    /**
     * @param list<string> $paymentSources
     */
    public function setPaymentSources(array $paymentSources): void
    {
        $this->paymentSources = $paymentSources;
    }
}
