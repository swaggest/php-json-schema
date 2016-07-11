<?php

namespace Yaoi\Schema\NumberFlavour;

use Yaoi\Schema\AbstractFlavour;

class Minimum extends AbstractFlavour
{
    public static function getSchemaKey()
    {
        return 'minimum';
    }
}