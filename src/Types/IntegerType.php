<?php

namespace Yaoi\Schema\Types;

use Yaoi\Schema\Validator;

class IntegerType extends AbstractType implements Validator
{
    public function isValid($data)
    {
        return is_int($data);
    }

}