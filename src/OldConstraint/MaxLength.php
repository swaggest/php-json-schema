<?php

namespace Yaoi\Schema\OldConstraint;

use Yaoi\Schema\AbstractConstraint;

class MaxLength extends AbstractConstraint
{
    public static function getSchemaKey()
    {
        return 'maxLength';
    }


}