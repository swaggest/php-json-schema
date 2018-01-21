<?php

namespace Swaggest\JsonSchema\Constraint;

use Swaggest\JsonSchema\Constraint\Format\IdnHostname;
use Swaggest\JsonSchema\Constraint\Format\Iri;
use Swaggest\JsonSchema\Constraint\Format\Uri;

class Format
{
    const DATE_REGEX_PART = '(\d{4})-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])';
    const TIME_REGEX_PART = '([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9]|60)(\.[0-9]+)?(Z|(\+|-)([01][0-9]|2[0-3]):([0-5][0-9]))?';
    /**
     * @see http://stackoverflow.com/a/1420225
     */
    const HOSTNAME_REGEX = '/^
      (?=.{1,255}$)
      [0-9a-z]
      (([0-9a-z]|-){0,61}[0-9a-z])?
      (\.[0-9a-z](?:(?:[0-9a-z]|-){0,61}[0-9a-z])?)*
      \.?
    $/ix';

    const JSON_POINTER_REGEX = '_^(?:/|(?:/[^/#]*)*)$_';
    const JSON_POINTER_RELATIVE_REGEX = '~^(0|[1-9][0-9]*)((?:/[^/#]*)*)(#?)$~';
    const JSON_POINTER_UNESCAPED_TILDE = '/~([^01]|$)/';

    public static function validationError($format, $data)
    {
        switch ($format) {
            case 'date-time':
                return self::dateTimeError($data);
            case 'date':
                return preg_match('/^' . self::DATE_REGEX_PART . '$/i', $data) ? null : 'Invalid date';
            case 'time':
                return preg_match('/^' . self::TIME_REGEX_PART . '$/i', $data) ? null : 'Invalid time';
            case 'uri':
                return Uri::validationError($data, Uri::IS_SCHEME_REQUIRED);
            case 'iri':
                return Iri::validationError($data);
            case 'email':
                return filter_var($data, FILTER_VALIDATE_EMAIL) ? null : 'Invalid email';
            case 'idn-email':
                return count(explode('@', $data, 3)) === 2 ? null : 'Invalid email';
            case 'ipv4':
                return filter_var($data, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ? null : 'Invalid ipv4';
            case 'ipv6':
                return filter_var($data, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ? null : 'Invalid ipv6';
            case 'hostname':
                return preg_match(self::HOSTNAME_REGEX, $data) ? null : 'Invalid hostname';
            case 'idn-hostname':
                return IdnHostname::validationError($data);
            case 'regex':
                return self::regexError($data);
            case 'json-pointer':
                return self::jsonPointerError($data);
            case 'relative-json-pointer':
                return self::jsonPointerError($data, true);
            case 'uri-reference':
                return Uri::validationError($data, Uri::IS_URI_REFERENCE);
            case 'iri-reference':
                return Iri::validationError($data, Uri::IS_URI_REFERENCE);
            case 'uri-template':
                return Uri::validationError($data, Uri::IS_URI_TEMPLATE);
        }
        return null;
    }

    public static function dateTimeError($data)
    {
        return preg_match('/^' . self::DATE_REGEX_PART . 'T' . self::TIME_REGEX_PART . '$/i', $data)
            ? null
            : 'Invalid date-time: ' . $data;
    }

    public static function regexError($data)
    {
        if (substr($data, -2) === '\Z') {
            return 'Invalid regex: \Z is not supported';
        }
        if (substr($data, 0, 2) === '\A') {
            return 'Invalid regex: \A is not supported';
        }


        $d = null;
        foreach (array('/', '_', '~', '#', '!', '%', '`', '=') as $delimiter) {
            if (strpos($data, $delimiter) === false) {
                $d = $delimiter;
                break;
            }
        }
        return @preg_match($d . $data . $d, '') === false ? 'Invalid regex: ' . $data : null;
    }

    public static function jsonPointerError($data, $isRelative = false)
    {
        if (preg_match(self::JSON_POINTER_UNESCAPED_TILDE, $data)) {
            return 'Invalid json-pointer: unescaped ~';
        }
        if ($isRelative) {
            return preg_match(self::JSON_POINTER_RELATIVE_REGEX, $data) ? null : 'Invalid relative json-pointer';
        } else {
            return preg_match(self::JSON_POINTER_REGEX, $data) ? null : 'Invalid json-pointer';
        }
    }
}