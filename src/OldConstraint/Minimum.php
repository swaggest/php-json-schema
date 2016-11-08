<?php

namespace Yaoi\Schema\OldConstraint;

use Yaoi\Schema\AbstractConstraint;

class Minimum extends AbstractConstraint
{
    public static function getSchemaKey()
    {
        return 'minimum';
    }
}