<?php

namespace Swaggest\JsonSchema;


class Helper
{
    public static function toPregPattern($jsonPattern)
    {
        static $delimiters = array('/', '#', '+', '~', '%');

        $pattern = false;
        foreach ($delimiters as $delimiter) {
            if (strpos($jsonPattern, $delimiter) === false) {
                $pattern = $delimiter . $jsonPattern . $delimiter . 'u';
                break;
            }
        }

        if (false === $pattern) {
            throw new InvalidValue('Failed to prepare preg pattern');
        }

        if (@preg_match($pattern, '') === false) {
            throw new InvalidValue('Regex pattern is invalid: ' . $jsonPattern);
        }

        return $pattern;
    }

    public static function resolveURI($parent, $current)
    {
        if (false !== $pos = strpos($current, '://')) {
            if (strpos($current, '/') > $pos) {
                return $current;
            }
        }

        if ($current === '') {
            return $parent;
        }

        $result = $parent;
        if ($current[0] === '#') {
            if (false !== $pos = strpos($parent, '#')) {
                $result = substr($parent, 0, $pos) . $current;
            }
        } else {
            if (false !== $pos = strrpos($parent, '/')) {
                $result = substr($parent, 0, $pos + 1) . $current;
            }
        }
        if (false === strpos($result, '#')) {
            $result .= '#';
        }
        return $result;
    }


}