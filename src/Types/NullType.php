<?php

namespace Yaoi\Schema\Types;
use Yaoi\Schema\TypeConstraint;

/**
 * For explicit setting of null value to property or variable
 */
class NullType implements TypeConstraint
{
    public static function get()
    {
        static $value;
        if (null === $value) {
            $value = new self();
        }
        return $value;
    }
}