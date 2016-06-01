<?php

namespace Yaoi\Schema\Types;

use Yaoi\Schema\Validator;

class BooleanType extends AbstractType implements Validator
{
    public function isValid($data)
    {
        return is_bool($data);
    }
}