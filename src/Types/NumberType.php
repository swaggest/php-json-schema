<?php

namespace Yaoi\Schema\Types;

use Yaoi\Schema\Validator;

class NumberType extends AbstractType implements Validator
{
    public function isValid($data)
    {
        return is_int($data) || is_float($data);
    }
}