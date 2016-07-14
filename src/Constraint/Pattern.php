<?php

namespace Yaoi\Schema\Constraint;


use Yaoi\Schema\AbstractConstraint;

class Pattern extends AbstractConstraint
{
    public static function getSchemaKey()
    {
        return 'pattern';
    }
}