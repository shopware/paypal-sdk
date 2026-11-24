<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Disputes\Item;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\Evidence\Document;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\Evidence\DocumentCollection;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\Evidence\EvidenceInfo;

#[OA\Schema(schema: 'paypal_v1_disputes_item_evidence')]
class Evidence extends Struct
{
    #[OA\Property(type: 'string', minLength: 1, maxLength: 255, pattern: '^[0-9A-Z_]+$')]
    protected string $evidenceType;

    #[OA\Property(ref: EvidenceInfo::class)]
    protected EvidenceInfo $evidenceInfo;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Document::class), minItems: 1, maxItems: 100)]
    protected DocumentCollection $documents;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 2000, pattern: '^(.|\\r?\\n)*$')]
    protected string $notes;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 255, pattern: '^[A-Za-z0-9]+$')]
    protected string $itemId;

    public function getEvidenceType(): string
    {
        return $this->evidenceType;
    }

    public function setEvidenceType(string $evidenceType): void
    {
        $this->evidenceType = $evidenceType;
    }

    public function getEvidenceInfo(): EvidenceInfo
    {
        return $this->evidenceInfo;
    }

    public function setEvidenceInfo(EvidenceInfo $evidenceInfo): void
    {
        $this->evidenceInfo = $evidenceInfo;
    }

    public function getDocuments(): DocumentCollection
    {
        return $this->documents;
    }

    public function setDocuments(DocumentCollection $documents): void
    {
        $this->documents = $documents;
    }

    public function getNotes(): string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): void
    {
        $this->notes = $notes;
    }

    public function getItemId(): string
    {
        return $this->itemId;
    }

    public function setItemId(string $itemId): void
    {
        $this->itemId = $itemId;
    }
}
