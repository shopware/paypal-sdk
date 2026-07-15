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
    schema: 'paypal_agentic_commerce_v1_agent_error_detail',
    required: ['field', 'issue', 'description']
)]
class AgentErrorDetail extends Struct
{
    #[OA\Property(type: 'string')]
    protected string $field;

    #[OA\Property(type: 'string')]
    protected string $issue;

    #[OA\Property(type: 'string')]
    protected string $description;

    public function getField(): string
    {
        return $this->field;
    }

    public function setField(string $field): void
    {
        $this->field = $field;
    }

    public function getIssue(): string
    {
        return $this->issue;
    }

    public function setIssue(string $issue): void
    {
        $this->issue = $issue;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}
