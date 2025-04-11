<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\Error;

use Shopware\PayPalSDK\Exception\ApiException;
use Shopware\PayPalSDK\Struct\Collection;

/**
 * @extends Collection<Detail>
 */
class DetailCollection extends Collection implements \Stringable
{
    public static function getExpectedClass(): string
    {
        return Detail::class;
    }

    /**
     * @return array<non-falsy-string>
     */
    public function getIssues(): array
    {
        /** @var array<non-falsy-string> $issues */
        $issues = $this->fmap(static fn (Detail $detail): ?string => $detail->getIssue());

        return $issues;
    }

    public function __toString(): string
    {
        $message = '';
        foreach ($this as $detail) {
            if ($detailIssue = $detail->getIssue()) {
                $message .= \sprintf('[%s] ', $detailIssue);
            }
            if ($field = $detail->getField()) {
                if ($value = $detail->getValue()) {
                    $message .= \sprintf('(%s: %s) ', $field, $value);
                } else {
                    $message .= \sprintf('(%s) ', $field);
                }
            }
            if ($description = $detail->getDescription()) {
                $message .= \sprintf('%s', $description);
            }
            $message .= ApiException::MESSAGE_DELIMITER;
        }

        if (\str_ends_with($message, ApiException::MESSAGE_DELIMITER)) {
            $message = \substr($message, 0, -\strlen(ApiException::MESSAGE_DELIMITER));
        }

        return $message;
    }
}
