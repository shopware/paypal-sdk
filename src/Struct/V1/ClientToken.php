<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v1_client_token')]
class ClientToken extends Struct
{
    #[OA\Property(type: 'string')]
    protected string $clientToken;

    /**
     * The lifetime of the access token, in seconds.
     */
    #[OA\Property(type: 'integer')]
    protected int $expiresIn;

    /**
     * Calculated expiration date
     */
    #[OA\Property(type: 'string', format: 'date-time')]
    protected \DateTime $expireDateTime;

    public function assign(array $data): static
    {
        $newToken = parent::assign($data);

        // Calculate the expiration date manually
        $expirationDateTime = new \DateTime('now', new \DateTimeZone('UTC'));
        $interval = \DateInterval::createFromDateString(\sprintf('%s seconds', $newToken->expiresIn));
        $newToken->expireDateTime = $expirationDateTime->add($interval ?: new \DateInterval('PT0S'));

        return $newToken;
    }

    public function getClientToken(): string
    {
        return $this->clientToken;
    }

    public function setClientToken(string $clientToken): void
    {
        $this->clientToken = $clientToken;
    }

    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    public function setExpiresIn(int $expiresIn): void
    {
        $this->expiresIn = $expiresIn;
    }

    public function getExpireDateTime(): \DateTime
    {
        return $this->expireDateTime;
    }

    public function setExpireDateTime(\DateTime $expireDateTime): void
    {
        $this->expireDateTime = $expireDateTime;
    }
}
