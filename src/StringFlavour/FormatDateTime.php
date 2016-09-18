<?php

namespace Yaoi\Schema\StringFlavour;


use Yaoi\Schema\Exception;

class FormatDateTime
{
    const REGEX = '/(\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2})(\.\d+)?(Z|([+-]\d{2}):?(\d{2}))/';

    public static function validate($data)
    {
        if (!preg_match(self::REGEX, strtoupper($data), $matches)) {
            throw new Exception('Invalid date-time', Exception::INVALID_VALUE);
        }
    }
}