<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Util;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Exception\ExceptionFactory;
use Shopware\PayPalSDK\Util\IssueCategory;
use Shopware\PayPalSDK\Util\IssueClassifier;

/**
 * @internal
 */
#[CoversClass(IssueClassifier::class)]
class IssueClassifierTest extends TestCase
{
    #[DataProvider('provideClassify')]
    public function testClassify(?IssueCategory $expected, int $status, string $body): void
    {
        static::assertSame($expected, IssueClassifier::classify(ExceptionFactory::createFromResponse(new Response($status, body: $body))));
    }

    public static function provideClassify(): \Generator
    {
        yield 'merchant' => [IssueCategory::MERCHANT, 422, self::error('UNPROCESSABLE_ENTITY', 'PAYEE_ACCOUNT_RESTRICTED')];
        yield 'closed merchant account as oauth error' => [IssueCategory::MERCHANT, 401, '{"error":"closed_user","error_description":"Closed user credentials"}'];
        yield 'risk decline of the token request' => [IssueCategory::MERCHANT, 401, '{"error":"RISK_DECLINE","error_description":"Request declined by risk"}'];
        yield 'merchant balance for refund' => [IssueCategory::MERCHANT, 422, self::error('UNPROCESSABLE_ENTITY', 'REFUND_FAILED_INSUFFICIENT_FUNDS')];
        yield 'buyer resolvable' => [IssueCategory::BUYER_RESOLVABLE, 422, self::error('UNPROCESSABLE_ENTITY', 'POSTAL_CODE_REQUIRED')];
        yield 'buyer' => [IssueCategory::BUYER, 422, self::error('UNPROCESSABLE_ENTITY', 'INSTRUMENT_DECLINED')];
        yield 'mistyped security code' => [IssueCategory::BUYER_RESOLVABLE, 422, self::error('UNPROCESSABLE_ENTITY', 'INVALID_SECURITY_CODE')];
        yield 'unverifiable buyer data' => [IssueCategory::BUYER_RESOLVABLE, 422, self::error('UNPROCESSABLE_ENTITY', 'PAYMENT_SOURCE_INFO_CANNOT_BE_VERIFIED')];
        yield 'address issue known from Shopware Payments' => [IssueCategory::BUYER_RESOLVABLE, 422, self::error('UNPROCESSABLE_ENTITY', 'ADDRESS_VALIDATION_ERROR')];
        yield 'payer transaction limit' => [IssueCategory::BUYER, 422, self::error('UNPROCESSABLE_ENTITY', 'TRANSACTION_LIMIT_EXCEEDED')];
        yield 'invalid payee in request' => [IssueCategory::LOGIC, 422, self::error('UNPROCESSABLE_ENTITY', 'PAYEE_ACCOUNT_INVALID')];
        yield 'most specific issue wins' => [IssueCategory::BUYER_RESOLVABLE, 422, self::error('UNPROCESSABLE_ENTITY', 'INSTRUMENT_DECLINED', 'POSTAL_CODE_REQUIRED')];
        yield 'unresolvable' => [IssueCategory::UNRESOLVABLE, 422, self::error('UNPROCESSABLE_ENTITY', 'AUTHORIZATION_EXPIRED')];
        yield 'charged back reference' => [IssueCategory::UNRESOLVABLE, 422, self::error('UNPROCESSABLE_ENTITY', 'PREVIOUS_TRANSACTION_REFERENCE_HAS_CHARGEBACK')];
        yield 'voided reference' => [IssueCategory::UNRESOLVABLE, 422, self::error('UNPROCESSABLE_ENTITY', 'PREVIOUS_TRANSACTION_REFERENCE_VOIDED')];
        yield 'retried capture of a captured order' => [null, 422, self::error('UNPROCESSABLE_ENTITY', 'ORDER_ALREADY_CAPTURED')];
        yield 'unresolvable wins over malformed request' => [IssueCategory::UNRESOLVABLE, 400, self::error('INVALID_REQUEST', 'PNREF_EXPIRED')];
        yield 'logic' => [IssueCategory::LOGIC, 422, self::error('UNPROCESSABLE_ENTITY', 'ITEM_TOTAL_MISMATCH')];
        yield 'malformed request' => [IssueCategory::LOGIC, 400, self::error('INVALID_REQUEST', 'INVALID_STRING_LENGTH')];
        yield 'buyer issue wins over malformed request' => [IssueCategory::BUYER_RESOLVABLE, 400, self::error('INVALID_REQUEST', 'POSTAL_CODE_REQUIRED')];
        yield 'technical issue' => [null, 422, self::error('UNPROCESSABLE_ENTITY', 'DUPLICATE_INVOICE_ID')];
        yield 'credentials' => [null, 401, '{"error":"invalid_client","error_description":"Client Authentication failed"}'];
        yield 'unavailable' => [null, 503, ''];
    }

    public function testClassifyCodes(): void
    {
        static::assertSame(IssueCategory::BUYER, IssueClassifier::classifyCodes('UNPROCESSABLE_ENTITY', 'CARD_EXPIRED'));
        static::assertNull(IssueClassifier::classifyCodes(null, 'RESOURCE_NOT_FOUND'));
        static::assertNull(IssueClassifier::classifyCodes());
    }

    private static function error(string $name, string ...$issues): string
    {
        return \json_encode([
            'name' => $name,
            'message' => 'Some message',
            'debug_id' => 'some-debug-id',
            'details' => \array_map(static fn (string $issue): array => ['issue' => $issue], $issues),
        ], \JSON_THROW_ON_ERROR);
    }
}
