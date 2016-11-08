<?php

namespace Yaoi\Schema\OldConstraint;


use Yaoi\Schema\AbstractConstraint;

class Pattern extends AbstractConstraint
{
    public static function getSchemaKey()
    {
        return 'pattern';
    }
}