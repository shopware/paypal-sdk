<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\Error;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Common\Link;
use Shopware\PayPalSDK\Struct\V1\Common\LinkCollection;

#[OA\Schema(schema: 'paypal_error')]
class Error extends Struct
{
    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $name = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $message = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $debugId = null;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Detail::class), nullable: true)]
    protected ?DetailCollection $details = null;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Link::class), nullable: true)]
    protected ?LinkCollection $links = null;

    /**
     * Only set if OAuth error occurs
     */
    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $error = null;

    /**
     * Only set if OAuth error occurs
     */
    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $errorDescription = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): void
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

    public function getDetails(): ?DetailCollection
    {
        return $this->details;
    }

    public function setDetails(?DetailCollection $details): void
    {
        $this->details = $details;
    }

    public function getLinks(): ?LinkCollection
    {
        return $this->links;
    }

    public function setLinks(?LinkCollection $links): void
    {
        $this->links = $links;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function setError(?string $error): void
    {
        $this->error = $error;
    }

    public function getErrorDescription(): ?string
    {
        return $this->errorDescription;
    }

    public function setErrorDescription(?string $errorDescription): void
    {
        $this->errorDescription = $errorDescription;
    }
}
