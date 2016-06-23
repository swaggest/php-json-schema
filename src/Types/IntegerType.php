<?php

namespace Yaoi\Schema\Types;

use Yaoi\Schema\Validator;

class IntegerType extends AbstractType implements Validator
{
    const TYPE = 'integer';

    public function isValid($data)
    {
        return is_int($data);
    }

}