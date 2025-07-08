<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1;

#[OA\Schema(
    schema: 'paypal_agentic_commerce_v1_error',
    required: ['name', 'message']
)]
class Error
{
    /**
     * Error name/type
     */
    #[OA\Property(type: 'string')]
    protected string $name;

    /**
     * Error description
     */
    #[OA\Property(type: 'string')]
    protected string $message;

    /**
     * Unique error identifier for support
     */
    #[OA\Property(type: 'string')]
    protected ?string $debugId = null;

    /**
     * Detailed error information
     *
     * @var list<array{field: string, issue: string, description: string}>
     */
    #[OA\Property(
        items: new OA\Items(
            required: ['field', 'issue', 'description'],
            properties: [
                new OA\Property(property: 'field', type: 'string'),
                new OA\Property(property: 'issue', type: 'string'),
                new OA\Property(property: 'description', type: 'string'),
            ],
            type: 'object'
        )
    )]
    protected ?array $details = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getDebugId(): ?string
    {
        return $this->debugId;
    }

    public function setDebugId(?string $debugId): void
    {
        $this->debugId = $debugId;
    }

    /**
     * @return ?list<array{field: string, issue: string, description: string}>
     */
    public function getDetails(): ?array
    {
        return $this->details;
    }

    public function addDetail(string $field, string $issue, string $description): void
    {
        $this->details[] = [
            'field' => $field,
            'issue' => $issue,
            'description' => $description,
        ];
    }

    public function resetDetails(): void
    {
        $this->details = [];
    }
}
