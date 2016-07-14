<?php

namespace Yaoi\Schema\Constraint;

use Yaoi\Schema\AbstractConstraint;

class Minimum extends AbstractConstraint
{
    public static function getSchemaKey()
    {
        return 'minimum';
    }
}