<?php
namespace Yaoi\Schema\Types;

use Yaoi\Schema\TypeConstraint;
use Yaoi\Schema\Validator;

class StringType extends AbstractType implements Validator, TypeConstraint
{
    
    public function isValid($data)
    {
        return is_string($data);
    }

}