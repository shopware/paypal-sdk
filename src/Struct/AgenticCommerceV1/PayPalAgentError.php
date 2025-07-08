<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerceV1;

use Shopware\PayPalSDK\Struct\Struct;

/**
 * @internal
 */
final class PayPalAgentError extends Struct
{
    protected string $name;

    protected int $code;

    protected string $message;

    protected ?string $debugId = null;

    protected ?PayPalAgentErrorDetailCollection $details = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function setCode(int $code): void
    {
        $this->code = $code;
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

    public function getDetails(): ?PayPalAgentErrorDetailCollection
    {
        return $this->details;
    }

    public function setDetails(?PayPalAgentErrorDetailCollection $details): void
    {
        $this->details = $details;
    }
}
