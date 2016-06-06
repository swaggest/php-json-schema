<?php
namespace Yaoi\Schema\Types;

use Yaoi\Schema\TypeConstraint;
use Yaoi\Schema\Validator;

class StringType extends AbstractType implements Validator, TypeConstraint
{
    const TYPE_STRING = 'string';

    public function isValid($data)
    {
        return is_string($data);
    }
}