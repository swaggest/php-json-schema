<?php

namespace Yaoi\Schema\OldConstraint;


use Yaoi\Schema\AbstractConstraint;

class MinLength extends AbstractConstraint
{
    public static function getSchemaKey()
    {
        return 'minLength';
    }
}