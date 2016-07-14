<?php

namespace Yaoi\Schema\Constraint;


use Yaoi\Schema\AbstractConstraint;

class MinLength extends AbstractConstraint
{
    public static function getSchemaKey()
    {
        return 'minLength';
    }
}