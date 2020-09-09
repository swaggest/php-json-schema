<?php

namespace Swaggest\JsonSchema\Constraint;

use Swaggest\JsonSchema\Constraint\Format\IdnHostname;
use Swaggest\JsonSchema\Constraint\Format\Iri;
use Swaggest\JsonSchema\Constraint\Format\Uri;

class Format
{
    const DATE_TIME = 'date-time';
    const DATE = 'date';
    const FULL_DATE = 'full-date';
    const TIME = 'time';
    const FULL_TIME = 'full-time';
    const URI = 'uri';
    const IRI = 'iri';
    const EMAIL = 'email';
    const IDN_EMAIL = 'idn-email';
    const IPV4 = 'ipv4';
    const IPV6 = 'ipv6';
    const HOSTNAME = 'hostname';
    const IDN_HOSTNAME = 'idn-hostname';
    const REGEX = 'regex';
    const JSON_POINTER = 'json-pointer';
    const RELATIVE_JSON_POINTER = 'relative-json-pointer';
    const URI_REFERENCE = 'uri-reference';
    const IRI_REFERENCE = 'iri-reference';
    const URI_TEMPLATE = 'uri-template';

    public static $strictDateTimeValidation = false;

    private static $dateRegexPart = '(\d{4})-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])';
    private static $timeRegexPart = '([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9]|60)(\.[0-9]+)?(Z|(\+|-)([01][0-9]|2[0-3]):?([0-5][0-9])?)?';
    private static $jsonPointerRegex = '_^(?:/|(?:/[^/#]*)*)$_';
    private static $jsonPointerRelativeRegex = '~^(0|[1-9][0-9]*)((?:/[^/#]*)*)(#?)$~';
    private static $jsonPointerUnescapedTilde = '/~([^01]|$)/';

    public static function validationError($format, $data)
    {
        switch ($format) {
            case self::DATE_TIME:
                return self::dateTimeError($data);
            case self::DATE:
            case self::FULL_DATE:
                return preg_match('/^' . self::$dateRegexPart . '$/i', $data) ? null : 'Invalid date';
            case self::TIME:
            case self::FULL_TIME:
                return preg_match('/^' . self::$timeRegexPart . '$/i', $data) ? null : 'Invalid time';
            case self::URI:
                return Uri::validationError($data, Uri::IS_SCHEME_REQUIRED);
            case self::IRI:
                return Iri::validationError($data);
            case self::EMAIL:
                return filter_var($data, FILTER_VALIDATE_EMAIL) ? null : 'Invalid email';
            case self::IDN_EMAIL:
                return count(explode('@', $data, 3)) === 2 ? null : 'Invalid email';
            case self::IPV4:
                return filter_var($data, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ? null : 'Invalid ipv4';
            case self::IPV6:
                return filter_var($data, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ? null : 'Invalid ipv6';
            case self::HOSTNAME:
                if (strlen(rtrim($data, '.')) >= 254) { // Not sure if it should be 254, higher number fails AJV suite.
                    return 'Invalid hostname (too long)';
                }
                return preg_match(Uri::HOSTNAME_REGEX, $data) ? null : 'Invalid hostname';
            case self::IDN_HOSTNAME:
                return IdnHostname::validationError($data);
            case self::REGEX:
                return self::regexError($data);
            case self::JSON_POINTER:
                return self::jsonPointerError($data);
            case self::RELATIVE_JSON_POINTER:
                return self::jsonPointerError($data, true);
            case self::URI_REFERENCE:
                return Uri::validationError($data, Uri::IS_URI_REFERENCE);
            case self::IRI_REFERENCE:
                return Iri::validationError($data, Uri::IS_URI_REFERENCE);
            case self::URI_TEMPLATE:
                return Uri::validationError($data, Uri::IS_URI_TEMPLATE);
        }
        return null;
    }

    public static function dateTimeError($data)
    {
        if (!preg_match('/^' . self::$dateRegexPart . 'T' . self::$timeRegexPart . '$/i', $data)) {
            return 'Invalid date-time format: ' . $data;
        }

        if (self::$strictDateTimeValidation) {
            $dt = date_create($data);
            if ($dt === false) {
                return 'Failed to parse date-time: ' . $data;
            }
            $isLeapSecond = '6' === $data[17] && (
                    0 === strpos(substr($data, 5, 5), '12-31') ||
                    0 === strpos(substr($data, 5, 5), '06-30')
                );
            if (!$isLeapSecond &&
                0 !== stripos($dt->format(DATE_RFC3339), substr($data, 0, 19))) {
                return 'Invalid date-time value: ' . $data;
            }
        }

        return null;
    }

    public static function regexError($data)
    {
        if (substr($data, -2) === '\Z') {
            return 'Invalid regex: \Z is not supported';
        }
        if (substr($data, 0, 2) === '\A') {
            return 'Invalid regex: \A is not supported';
        }

        return @preg_match('{' . $data . '}', '') === false ? 'Invalid regex: ' . $data : null;
    }

    public static function jsonPointerError($data, $isRelative = false)
    {
        if (preg_match(self::$jsonPointerUnescapedTilde, $data)) {
            return 'Invalid json-pointer: unescaped ~';
        }
        if ($isRelative) {
            return preg_match(self::$jsonPointerRelativeRegex, $data) ? null : 'Invalid relative json-pointer';
        } else {
            return preg_match(self::$jsonPointerRegex, $data) ? null : 'Invalid json-pointer';
        }
    }
}