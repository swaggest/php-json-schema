<?php

namespace Yaoi\Schema\Constraint;

use Yaoi\Schema\AbstractConstraint;

class MinItems extends AbstractConstraint
{
    public static function getSchemaKey()
    {
        return 'minItems';
    }
}