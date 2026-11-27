# Migration

## 2.x to 3.0

- PHP 8.4 or newer is required.
- Requests send `Prefer: return=representation` by default, token requests included. Use `$context->withPreferRepresentation(false)` for `return=minimal`.
- Cached 2.x tokens do not work with 3.x. Clear the token cache, or use a separate cache namespace during rolling deployments.
- `Order::$intent` no longer defaults to `CAPTURE`. Set it explicitly.
- Class and interface constants declare their types. Overrides must declare compatible types.
- `RequestService` takes separate request, stream and URI factories: `new RequestService($factory, $factory, $factory)`.
- `TokenGatewayInterface::getToken()` takes `bool $refresh = false`. Custom implementations must accept it and bypass the cache when it is `true`.
- `PaymentGateway::voidAuthorization()` returns `?Authorization`, which is `null` for an empty response.
- `Webhook\Event::identifyResourceType()` is private. Override `identifyResource()` instead.
- `Token` and `ClientToken` expiration dates are `DateTimeImmutable`. Pass the result of `modify()` to `setExpireDateTime()`.
- A REST error with HTTP 401 is an `ErrorApiException` with its details. An unstructured 401 response is an `ApiException`.
- `$collection[] = $element` adds and validates the element.
- `Struct::from(Order::class, $data)` is `Order::from($data)`, and `Collection::createFromAssociative()` is `Collection::from()`.
- `Struct::jsonSerialize()` omits `null` properties and empty collections, and lists inherited properties first. Structs no longer filter their output with `array_filter()`, so `false`, `0` and `''` are serialized.
- An empty struct is serialized as an object: `jsonSerialize()` holds a `\stdClass` for an empty nested struct or collection element, so `json_encode()` writes `{}`, and an empty request body is sent as `{}`.
- Collection getters return an empty collection instead of `null` or throwing, and keep it, so `$order->getLinks()->add($link)` persists. Collection setters accept `null`. Array getters return `[]` instead of `null`.
- Structs no longer preset fields, so a request only carries what you set. Set these where you relied on the old value:
  - `V2\Order\ApplicationContext` and `PaymentSource\Common\ExperienceContext`: `landingPage` (`NO_PREFERENCE`), `shippingPreference` (`SET_PROVIDED_ADDRESS`), `userAction` (`PAY_NOW`)
  - `V1\Payment::$intent` (`sale`); `V1\Payment\ApplicationContext`: `shippingPreference` (`SET_PROVIDED_ADDRESS`), `userAction` (`commit`); `V1\Subscription\ApplicationContext`: `shippingPreference` (`SET_PROVIDED_ADDRESS`), `userAction` (`SUBSCRIBE_NOW`)
  - `PaymentSource\Common\Attributes`: `Vault::$permitMultiplePaymentTokens` (`true`), `Verification::$method` (`SCA_ALWAYS`), `Token::$type` (`BANK_REFERENCE_TOKEN`), `Mandate::$type` (`ONE_OFF`); `AppSwitchContext\MobileWebContext::$returnFlow` (`AUTO`)
  - `V2\Order\Tracker::$notifyPayer` (`false`); `PurchaseUnit\ShippingOption`: `type` (`SHIPPING`), `selected` (`false`)
  - `V2\Referral`: `products` and `capabilities`; `Referral\Operation::$operation` (`API_INTEGRATION`); `RestApiIntegration`: `integrationMethod` (`PAYPAL`), `integrationType` (`THIRD_PARTY`); `PartnerConfigOverride::$partnerLogoUrl`; `LegalConsent::$granted` (`true`)
  - `FindEligibleMethods\Preferences\PaymentSourceConstraint`: `constraintType` (`INCLUDE`), `paymentSources` (`[]`)
  - AgenticCommerce: `GiftOptions::$isGift`, `Value\GiftRecipientEmailValue::$verified`, `Value\AgeVerificationValue::$confirmed` (all `false`)
- `Reporting\Balance::isPrimary()`, `V2\Order\Tracker::isNotifyPayer()`, `Attributes\Vault::isPermitMultiplePaymentTokens()` and `GiftRecipientEmailValue::isVerified()` return `?bool`.
- Boolean getters start with `is`, nullable or not: `Transactions\CartInfo::getTaxInclusive()`, `GiftOptions::getGiftWrap()`, `PricingErrorContext::getRequiredCurrencyConsistency()`, `ShippingErrorContext::getPoBoxDetected()` and `InventoryIssueContext::getUpgradeAvailable()` are now `is…()`.
- `Webhook\Event::getResourceVersion()` and `getResourceType()` return `null` when PayPal leaves the field out. Such resources still resolve as version `1.0`.
- Optional request fields are nullable, e.g. the URLs, `brandName` and `locale` of `ExperienceContext` and `V2\Order\ApplicationContext`, `FindEligibleMethods\Preferences`, `Patch::$from` and the referral operation fields.
- `Struct::assign()` throws a `LogicException` for a property typed as an abstract struct or as a union with a struct or collection. Override `assign()` to hydrate such a property. An array for a property that accepts none is skipped instead of throwing a `TypeError`.

The structs are now checked against PayPal's OpenAPI specs (see [`tools/openapi/README.md`](tools/openapi/README.md)):

- Fields PayPal can leave out return `null` instead of throwing, and their setters accept `null`. This affects `Order`, `PurchaseUnit` and many nested structs, for example `Payments\Payment::$createTime`, `Shipping::$address`, `Payer::$address` and the vault IDs of `VaultedPaymentSourceInterface`.
- `PurchaseUnit\Item::MAX_LENGTH_NAME` is 127 UTF-16 code units, so an emoji counts twice. Shorten names with `Utf16::truncate($name, Item::MAX_LENGTH_NAME)` instead of `mb_substr()`.
- `Shipping\Tracker\Item` no longer limits the name, and its `MAX_LENGTH_NAME` is removed.
- `Shipping\Tracker::isNotifyPayer()` returns `?bool`.
- `SupplementaryData\Card` holds `level2` and `level3`, and `SupplementaryData\Risk` holds `customer`. Their `address` is removed.
- `StoredCredential::$previousNetworkTransactionReference` is a `NetworkTransactionReference` object instead of a string.
- `PaymentSource::$myBank` is `$mybank` and is now sent as `mybank`. `getMyBank()` and `setMyBank()` keep working.
- Disputes: `MoneyMovement::$amount` is `V1\Common\Money`. Sub-reasons and refund IDs are string arrays, and `SubReason`, `RefundId` and their collections are removed.
- `#[OA\Property]` types `bool` and `float` are now `boolean` and `number`, which changes generated TypeScript types.
- `#[OA\Property]` no longer sets `nullable`, since null is never serialized. `#[OA\Schema(required: …)]` lists the non-nullable properties, the fields that are always present, so generated TypeScript types mark the others optional instead of `| null`.
- Fields their schema documents as optional are nullable: `CheckoutField::$value`, `ResolutionOption::$metadata`, `GiftRecipientNameValue::$firstName` and `$lastName`, `MerchantIntegrations\OauthIntegration::$status`, `OauthThirdParty::$accessToken` and `$refreshToken`, `MerchantIntegrations\Product::$status` and `$capabilities`.
