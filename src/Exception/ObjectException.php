<?php

namespace Yaoi\Schema\Exception;

use Yaoi\Schema\InvalidValue;

class ObjectException extends InvalidValue
{
    const REQUIRED = 1;
    const TOO_MANY = 2;
    const TOO_FEW = 3;
    const DEPENDENCY_MISSING = 4;

}