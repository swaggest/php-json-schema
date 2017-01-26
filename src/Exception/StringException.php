<?php

namespace Yaoi\Schema\Exception;


use Yaoi\Schema\InvalidValue;

class StringException extends InvalidValue
{
    const TOO_SHORT = 1;
    const TOO_LONG = 2;
    const PATTERN_MISMATCH = 3;

}