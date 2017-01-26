<?php

namespace Yaoi\Schema;


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
            throw new Exception('Failed to prepare preg pattern');
        }

        if (@preg_match($pattern, '') === false) {
            throw new Exception('Regex pattern is invalid: ' . $jsonPattern);
        }

        return $pattern;
    }

}