<?php

namespace Yaoi\Schema\OldConstraint;

use Yaoi\Schema\AbstractConstraint;

class MinItems extends AbstractConstraint
{
    public static function getSchemaKey()
    {
        return 'minItems';
    }
}