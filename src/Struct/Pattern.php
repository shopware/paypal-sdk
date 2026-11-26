<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct;

/**
 * Regular expressions for `#[OA\Property(pattern: ...)]`.
 */
final class Pattern
{
    public const string DATE = '^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$';

    /**
     * RFC 3339
     */
    public const string DATE_TIME = '^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])[T,t]([0-1][0-9]|2[0-3]):[0-5][0-9]:([0-5][0-9]|60)([.][0-9]+)?([Zz]|[+-][0-9]{2}:[0-9]{2})$';

    /**
     * `HH:MM`, 24-hour clock
     */
    public const string TIME = '^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$';

    /**
     * ISO 3166-1 alpha-2, or `C2` for China worldwide
     */
    public const string COUNTRY_CODE = '^([A-Z]{2}|C2)$';

    /**
     * ISO 3166-1 alpha-2
     */
    public const string ISO_COUNTRY_CODE = '^[A-Z]{2}$';

    /**
     * BCP 47 tag with optional script and region, e.g. `de-DE`, `zh-Hant-TW` or `es-419`
     */
    public const string LANGUAGE = '^[a-z]{2}(?:-[A-Z][a-z]{3})?(?:-(?:[A-Z]{2}|[0-9]{3}))?$';

    /**
     * Like {@see self::LANGUAGE}, without numeric regions such as `es-419`
     */
    public const string LANGUAGE_ALPHA_REGION = '^[a-z]{2}(?:-[A-Z][a-z]{3})?(?:-(?:[A-Z]{2}))?$';

    /**
     * PayPal payer or merchant ID
     */
    public const string ACCOUNT_ID = '^[2-9A-HJ-NP-Z]{13}$';

    public const string ACQUIRER_REFERENCE_NUMBER = '^[a-zA-Z0-9]+$';

    public const string BILLING_AGREEMENT_ID = '^[a-zA-Z0-9-]+$';

    public const string CARD_LAST_DIGITS = '^[0-9]{2,4}$';

    public const string DISPUTE_CHANNEL = '^[A-Z0-9_]+$';

    public const string DISPUTE_MERCHANT_ID = '^[0-9A-Za-z]+$';

    public const string INVOICE_ID = '^[\w‘\-.,":;\!?]*$';

    public const string LEVEL_3_CODE = '^[a-zA-Z0-9_\'.-]*$';

    public const string NETWORK_TRANSACTION_ID = '^[a-zA-Z0-9-_@.:&+=*^\'~#!$%()]+$';

    public const string UPC = '^[0-9]{0,17}$';

    public const string VERSION = '^([0-9]+.[0-9]+)$';

    /**
     * `Y` or `N`
     */
    public const string YES_NO = '^[N|Y]$';

    /**
     * Optionally signed, e.g. `-1`, `.5` or `10.00`
     */
    public const string DECIMAL = '^((-?[0-9]+)|(-?([0-9]+)?[.][0-9]+))$';

    public const string DIGITS = '^[0-9]+$';

    /**
     * Up to 10 digits
     */
    public const string POSITIVE_INTEGER = '^[1-9][0-9]{0,9}$';

    public const string LATITUDE = '^-?([1-8]?[0-9](\.\d+)?|90(\.0+)?)$';

    public const string LONGITUDE = '^-?((1[0-7]|[1-9])?[0-9](\.\d+)?|180(\.0+)?)$';

    public const string PHONE_COUNTRY_CODE = '^[0-9]{1,3}?$';

    public const string PHONE_NATIONAL_NUMBER = '^[0-9]{1,14}?$';

    public const string PHONE_EXTENSION = '^[0-9]{1,15}?$';

    /**
     * RFC 5322
     */
    public const string EMAIL_ADDRESS = '^(?:[A-Za-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\.[A-Za-z0-9!#$%&\'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?\.)+[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[A-Za-z0-9-]*[A-Za-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])$';

    /**
     * Any text that contains an RFC 5322 address
     */
    public const string CONTAINS_EMAIL_ADDRESS = '^.*(?:[a-zA-Z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+/=?^_`{|}~-]+)*|(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?|\[(?:(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9]))\.){3}(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9])|[a-zA-Z0-9-]*[a-zA-Z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\]).*$';

    /**
     * An `@` with text on both sides
     */
    public const string LOOSE_EMAIL_ADDRESS = '^.+@[^"\-].+$';

    /**
     * IPv4, IPv6 or host name
     */
    public const string IP_ADDRESS_OR_HOST_NAME = '^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$|^(([a-zA-Z]|[a-zA-Z][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z]|[A-Za-z][A-Za-z0-9\-]*[A-Za-z0-9])$|^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?\s*$';

    /**
     * Letters, digits, spaces and `_'-.,":;!?`, or empty
     */
    public const string TEXT = '^[a-zA-Z0-9_\'\-., ":;\!?]*$';

    public const string MULTILINE_TEXT = '^(.|\r?\n)*$';

    /**
     * Anything but `~!@#$%^*()_{}:|/`, tabs and line breaks
     */
    public const string NAME = '^[^~!@#$%^*()_{}:|\t\n/]+$';

    /**
     * With an extension
     */
    public const string FILE_NAME = '^[A-Za-z0-9-_,\s]+[.]{1}[A-Za-z]+$';

    /**
     * Letters, digits, spaces and `_,.-`
     */
    public const string DEVICE_INFO = '^[0-9a-zA-Z_,. -]+$';

    public const string ALNUM = '^[A-Za-z0-9]+$';

    public const string ALNUM_OR_EMPTY = '^[a-zA-Z0-9]*$';

    public const string ALNUM_DOT_OR_EMPTY = '^[a-zA-Z0-9.]*$';

    public const string ALNUM_HYPHEN = '^[A-Za-z0-9-]+$';

    public const string ALNUM_HYPHEN_OR_EMPTY = '^[a-zA-Z0-9-]*$';

    public const string ALNUM_COLON_HYPHEN = '^[A-Za-z0-9:\-]+$';

    public const string ALNUM_COLON_HYPHEN_PIPE = '^[A-Za-z0-9:\-|]+$';

    public const string ALNUM_DOT_UNDERSCORE_HYPHEN = '^[A-Za-z0-9._-]+$';

    public const string ALNUM_UNDERSCORE_HYPHEN = '^[0-9a-zA-Z_-]+$';

    public const string ALNUM_UNDERSCORE_HYPHEN_OR_EMPTY = '^[-a-zA-Z0-9_]*$';

    public const string UPPER_ALNUM = '^[A-Z0-9]+$';

    public const string UPPER_ALNUM_HYPHEN = '^[A-Z0-9-]+$';

    public const string UPPER_ALNUM_UNDERSCORE = '^[0-9A-Z_]+$';

    public const string UPPER_ALNUM_UNDERSCORE_HYPHEN = '^[0-9A-Z_-]+$';

    public const string UPPER_UNDERSCORE = '^[A-Z_]+$';
}
