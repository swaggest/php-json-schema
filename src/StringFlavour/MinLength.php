<?php

namespace Yaoi\Schema\StringFlavour;


use Yaoi\Schema\AbstractFlavour;

class MinLength extends AbstractFlavour
{
    public static function getSchemaKey()
    {
        return 'minLength';
    }
}