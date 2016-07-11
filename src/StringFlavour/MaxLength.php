<?php

namespace Yaoi\Schema\StringFlavour;


use Yaoi\Schema\AbstractFlavour;

class MaxLength extends AbstractFlavour
{
    public static function getSchemaKey()
    {
        return 'maxLength';
    }


}