<?php

namespace Yaoi\Schema\Exception;


use Yaoi\Schema\InvalidValue;

class NumericException extends InvalidValue
{
    const MULTIPLE_OF = 1;
    const MAXIMUM = 2;
    const MINIMUM = 3;

}