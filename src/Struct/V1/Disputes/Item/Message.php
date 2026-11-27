<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Disputes\Item;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Pattern;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v1_disputes_item_message', required: ['postedBy', 'timePosted', 'content'])]
class Message extends Struct
{
    #[OA\Property(type: 'string', minLength: 1, maxLength: 255, pattern: Pattern::UPPER_ALNUM_UNDERSCORE)]
    protected string $postedBy;

    #[OA\Property(type: 'string', format: 'date-time', minLength: 20, maxLength: 64, pattern: Pattern::DATE_TIME)]
    protected string $timePosted;

    #[OA\Property(type: 'string', maxLength: 2000, pattern: Pattern::MULTILINE_TEXT)]
    protected string $content;

    public function getPostedBy(): string
    {
        return $this->postedBy;
    }

    public function setPostedBy(string $postedBy): void
    {
        $this->postedBy = $postedBy;
    }

    public function getTimePosted(): string
    {
        return $this->timePosted;
    }

    public function setTimePosted(string $timePosted): void
    {
        $this->timePosted = $timePosted;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}
