<?php

namespace Yaoi\Schema\ArrayFlavour;


use Yaoi\Schema\AbstractFlavour;
use Yaoi\Schema\Flavour;

class MinItems extends AbstractFlavour implements Flavour
{
    public static function getSchemaKey()
    {
        return 'minItems';
    }
}