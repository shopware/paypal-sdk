# OpenAPI conformance check

Checks the hand-written structs against
[PayPal's OpenAPI specifications](https://github.com/paypal/paypal-rest-api-specifications)
and reports where they disagree. The structs stay the source of truth: nothing is
generated, moved or renamed. The spec is used as an oracle.

```bash
composer openapi:check                        # findings not in the baseline; exits 1 if any
composer openapi:check -- --kind=type,enum    # only these kinds
composer openapi:check -- --kind=spec-only    # fields the spec defines but the SDK does not model
composer openapi:check -- --no-baseline       # ignore the baseline
composer openapi:check -- --update-baseline   # baseline every current finding
composer openapi:check -- --fix               # write the spec's facets into #[OA\Property]
```

Specs are fetched at the commit pinned in [`SpecRepository::REF`](SpecRepository.php)
and cached under `var/openapi/specs/{ref}/`. The tool is a dev tool and not part of
the package (`export-ignore`).

## How structs are paired with schemas

[`Roots::operations()`](Roots.php) lists the API operations the SDK calls, with the
struct it sends and the one it receives. That is the only hand-written mapping. From
there the struct tree and the schema tree are walked in parallel along the property
names: wherever a property holds another struct (or a collection of one), that struct
is paired with the schema the spec gives the same field. A body that is a JSON array,
like a PATCH request, pairs its element struct.

A struct reached through several paths collects every schema it stands in for.
`PaymentSource\Card` is paired with `card_request`, `card_response`,
`apple_pay_card_response` and others, and is held to their union: a field counts as
modelled if any of them defines it, an enum value if any of them lists it.

Every struct in `src/Struct` must be reached from an operation, or be listed in
[`Roots::UNCOVERED`](Roots.php) with the reason (no published spec, query parameters,
not referenced by anything). A struct that is neither is an `unpaired` finding.

## Findings

| kind         | meaning                                                                                       | fails the check |
| ------------ | --------------------------------------------------------------------------------------------- | --------------- |
| `type`       | the SDK type cannot hold what the spec sends: assigning throws, or the value is silently lost | yes             |
| `getter`     | the getter throws when the field is absent, and a full response example omits it               | yes             |
| `attribute`  | an `#[OA\Property]` length, pattern, format or bound differs from the spec                    | yes (`--fix`)   |
| `enum`       | an enum value only one of SDK and spec knows                                                   | yes             |
| `field`      | the property is read or written under another name than the spec's field                     | yes             |
| `sdk-only`   | the SDK models a field no paired schema defines                                               | yes             |
| `unpaired`   | no operation reaches the struct and `Roots::UNCOVERED` does not list it                       | yes             |
| `spec-only`  | a paired schema defines a field the SDK does not model                                        | no              |

Fields are matched the way `Struct::assign()` matches them
(`CaseConverter::denormalize()`), so `line1` pairs with `line1`. A property that
matches a field only up to case and underscores (`$myBank` for `mybank`) is a
`field` finding: `assign()` never reads it and `jsonSerialize()` writes `my_bank`.
For sent structs, `field` also checks what `jsonSerialize()` writes for each
property. A setter that coerces the spec's type (`setQuantity(int|string)`) is not a
`type` finding.

### Which getters may throw

The SDK sends `Prefer: return=representation`, so responses carry the full
resource. PayPal's response schemas cannot say which fields that includes: one
schema also describes `return=minimal` (only `id`, `status`, `links`), so it marks
next to nothing `required`. Deriving nullability from `required` would make
nearly every getter nullable.

Instead the documented response examples serve as evidence. Every full example
(minimal ones are skipped) is walked along the struct tree. A getter is reported
only if it throws on a fresh instance *and* an example omits its field although it
carries the parent object: `Capture::$processorResponse` (cards only),
`Shipping::$name` (pickup in store), `Payer::$address`. A field no example omits
stays non-nullable. A caller that opts back into `return=minimal` should read only
`id`, `status` and `links`.

Only the orders and payments specs document response examples, so the getters of
the disputes, webhooks, reporting, referral and vault structs are not checked.

### Attribute facets

`format`, `minLength`, `maxLength`, `pattern`, `minimum`, `maximum`, `minItems` and
`maxItems` on `#[OA\Property]` are owned by the spec. Everything else on the
attribute (type, ref, items, enum, nullable, default) is owned by the SDK.

- A struct paired with several schemas gets the loosest facets that hold for all
  of them. Request schemas take precedence when there are any, because what may
  be *sent* is the contract. `item_request.name` allows 3000 characters while the
  `item` response says 127.
- Facets that mean nothing are dropped: `minLength: 0`, `maxItems: 32767`,
  `pattern: '^[\S\s]*$'`.
- PayPal's formats are mapped to standard ones (`ppaas_date_time_v3` ->
  `date-time`, `ppaas_date_notime_v2` -> `date`, `*_email_address_*` ->
  `email`). PayPal-internal formats are dropped.
- A facet bound to a constant (`maxLength: self::MAX_LENGTH_NAME`) is an SDK
  limit the code enforces. `--fix` keeps it, and the check reports it until the
  constant is changed or the finding is baselined.

`--fix` sets or removes single facets, keeps the attribute's layout and comments,
and skips baselined findings. Run `composer ecs-fix` afterwards.

## Baseline

[`baseline.php`](baseline.php) maps the id of each deliberate finding to the reason it
is deliberate: payment sources PayPal does not publish, SDK-side fields, limits that
differ from the spec on purpose. A baselined finding is not reported.

The id names one deviation, not just the property, so a different deviation on the
same property is reported again:

```
attribute V2\Order\PurchaseUnit\Item::$name maxLength 127 -> 3000
enum V2\Order\PaymentSource\Common\ExperienceContext::$landingPage +BILLING
sdk-only V2\Order\PaymentSource::$payUponInvoice
```

`--update-baseline` keeps the reasons of known entries and adds new ones with a TODO
to replace. An entry that no longer matches a finding fails the check as stale, and
so does a `Roots::UNCOVERED` entry that matches no struct, so neither list can rot.

## Bumping the specs

Set `SpecRepository::REF` to a newer commit, run the check and review what changed.
New `type`/`getter` findings are struct bugs. `attribute` findings are updated
limits: review them, then run `--fix`. Run with `--kind=spec-only` to see the fields
PayPal added.
