# OpenAPI conformance check

Compares the structs in `src/Struct` with
[PayPal's OpenAPI specs](https://github.com/paypal/paypal-rest-api-specifications)
and reports where they disagree. It runs in the `openapi` job of the lint workflow.
Only `--fix` changes the structs, and only the facets of `#[OA\Property]`.

```bash
composer openapi:check                        # findings that are not baselined
composer openapi:check -- --kind=type,enum    # only these kinds
composer openapi:check -- --kind=spec-only    # fields PayPal defines that the SDK does not model
composer openapi:check -- --no-baseline       # ignore the baseline
composer openapi:check -- --fix               # write the spec's facets into #[OA\Property]
composer openapi:check -- --update-baseline   # baseline every current finding
```

The check exits 1 on a finding that is not baselined, and on a baseline or
`Roots::UNCOVERED` entry that matches nothing anymore. Specs are downloaded at the
commit pinned in [`SpecRepository::REF`](SpecRepository.php) and cached in
`var/openapi/specs/`.

## Fixing findings

| kind        | meaning                                                                         | fix                                                            |
| ----------- | ------------------------------------------------------------------------------- | -------------------------------------------------------------- |
| `type`      | the property type cannot hold the spec's value: assigning throws or loses data  | change the type                                                |
| `getter`    | the getter throws on a missing field, and a full response example omits it      | make the getter nullable                                       |
| `attribute` | a length, pattern, format or bound in `#[OA\Property]` differs from the spec    | `--fix`                                                        |
| `enum`      | an enum value only one of SDK and spec knows                                    | add or remove the value                                        |
| `field`     | the property is read or written under another name than the spec's field       | rename the property                                            |
| `sdk-only`  | no paired schema defines the field                                              | remove the property, or baseline it if PayPal sends it anyway  |
| `unpaired`  | no operation reaches the struct, and `Roots::UNCOVERED` does not list it        | see [Adding an endpoint](#adding-an-endpoint-or-a-struct)      |
| `spec-only` | the spec defines a field the SDK does not model; never fails the check          | model it when needed                                           |

A deliberate deviation goes into the [baseline](#baseline) instead.

`--fix` skips baselined findings and keeps the attribute's layout and comments. It
writes patterns as [`Pattern`](../../src/Struct/Pattern.php) constants. A pattern
without a constant is written as a literal, and
[`PatternTest`](../../tests/unit/Struct/PatternTest.php) fails until it is added to
`Pattern`. Run `composer ecs-fix` afterwards.

## Baseline

[`baseline.php`](baseline.php) maps each deliberate finding to the reason. The key
names the deviation, not just the property, so a new deviation on the same
property is reported again:

```php
'attribute V2\Order\PurchaseUnit\Item::$name maxLength 127 -> 3000' => 'PayPal truncates longer names and may split a surrogate pair, see Utf16::truncate()',
'sdk-only V2\Order\PaymentSource::$payUponInvoice' => 'Pay Upon Invoice payment source, missing from the published spec',
```

`--update-baseline` keeps existing reasons and adds new entries with a TODO;
replace each TODO with the reason. An entry that matches no finding anymore fails
the check and has to be removed.

## Adding an endpoint or a struct

- A struct nested in a paired struct is paired automatically.
- A new endpoint needs an `Operation` in [`Roots::operations()`](Roots.php) with
  the spec, method, path and the structs it sends and receives.
- A struct no spec describes goes into `Roots::UNCOVERED` with the reason, e.g.
  `'AgenticCommerce' => 'no published spec'`.

## Bumping the specs

Set `SpecRepository::REF` to a newer commit and run the check.

- `type`, `getter` and `field` findings are struct bugs.
- `attribute` findings are changed limits: review them, then run `--fix`.
- Stale baseline entries are deviations PayPal resolved: remove them.
- `--kind=spec-only` lists the fields PayPal added.

## How it works

### Pairing

[`Roots::operations()`](Roots.php) is the only hand-written mapping. From each
operation, the struct and schema trees are walked in parallel along the property
names, and every nested struct is paired with the schema of the same field. For
an array body, like a PATCH request, the element struct is paired.

A struct reached on several paths is held to the union of its schemas.
`PaymentSource\Card` is paired with `card_request`, `card_response`,
`apple_pay_card_response` and others: a field counts as modelled if any of them
defines it, an enum value if any of them lists it.

Fields are matched the way `Struct::assign()` matches them
(`CaseConverter::denormalize()`). A property that matches a field only up to case
and underscores, like `$myBank` for `mybank`, is a `field` finding: `assign()`
never fills it and `jsonSerialize()` writes `my_bank`. For sent structs, the keys
`jsonSerialize()` writes are checked as well. A property whose setter converts the
spec's type, like `setQuantity(int|string)`, is not a `type` finding.

### Getters

The SDK sends `Prefer: return=representation`, but the same response schemas
describe `return=minimal` and so mark almost nothing `required`. The full response
examples serve as evidence instead: a getter is reported only if it throws on a
fresh instance and an example omits its field while including the parent object,
like `Capture::$processorResponse`, which only card payments carry. Only the orders
and payments specs have examples, so other getters are not checked.

### Facets

`format`, `minLength`, `maxLength`, `pattern`, `minimum`, `maximum`, `minItems` and
`maxItems` come from the spec. All other arguments of `#[OA\Property]` belong to
the SDK.

- A struct paired with several schemas gets the loosest facets that hold for all of
  them. Request schemas win over response schemas, because what may be sent is the
  contract: `item_request.name` allows 3000 characters, the `item` response 127.
- Facets that constrain nothing are dropped: `minLength: 0`, `maxItems: 32767`,
  `pattern: '^[\S\s]*$'`.
- PayPal's formats are mapped to standard ones (`ppaas_date_time_v3` ->
  `date-time`). PayPal-internal formats are dropped.
- A facet bound to a constant other than `Pattern`, like
  `maxLength: self::MAX_LENGTH_NAME`, is an SDK limit. `--fix` leaves it alone, and
  the check reports it until the constant changes or the finding is baselined.
