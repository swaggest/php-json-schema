<?php

namespace Yaoi\Schema\Constraint;

use Yaoi\Schema\AbstractConstraint;

class MaxLength extends AbstractConstraint
{
    public static function getSchemaKey()
    {
        return 'maxLength';
    }


}