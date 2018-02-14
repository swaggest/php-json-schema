<?php

namespace Swaggest\JsonSchema\Constraint\Format;

class Uri
{
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

    const IS_URI_REFERENCE = 1;
    const IS_URI_TEMPLATE = 2;
    const IS_SCHEME_REQUIRED = 8;

    public static function validationError($data, $options = 0)
    {
        if ($options === Uri::IS_URI_TEMPLATE) {
            $opened = false;
            for ($i = 0; $i < strlen($data); ++$i) {
                if ($data[$i] === '{') {
                    if ($opened) {
                        return 'Invalid uri-template: unexpected "{"';
                    } else {
                        $opened = true;
                    }
                } elseif ($data[$i] === '}') {
                    if ($opened) {
                        $opened = false;
                    } else {
                        return 'Invalid uri-template: unexpected "}"';
                    }
                }
            }
            if ($opened) {
                return 'Invalid uri-template: unexpected end of string';
            }
        }

        $uri = parse_url($data);
        if (!$uri) {
            return 'Malformed URI';
        }
        if (($options & self::IS_SCHEME_REQUIRED) && (!isset($uri['scheme']) || $uri['scheme'] === '')) {
            return 'Missing scheme in URI';
        }
        if (isset($uri['host'])) {
            $host = $uri['host'];
            if (!preg_match(self::HOSTNAME_REGEX, $host)) {
                // stripping [ ]
                if ($host[0] === '[' && $host[strlen($host) - 1] === ']') {
                    $host = substr($host, 1, -1);
                }
                if (!filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                    return 'Malformed host in URI: ' . $host;
                }
            }
        }

        if (isset($uri['path'])) {
            if (strpos($uri['path'], '\\') !== false) {
                return 'Invalid path: unescaped backslash';
            }
        }

        if (isset($uri['fragment'])) {
            if (strpos($uri['fragment'], '\\') !== false) {
                return 'Invalid fragment: unescaped backslash';
            }
        }

        return null;
    }
}