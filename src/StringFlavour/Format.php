<?php

namespace Yaoi\Schema\StringFlavour;

use Yaoi\Schema\AbstractFlavour;

class Format extends AbstractFlavour
{
    const FORMAT_URI = 'uri';

    public static function getSchemaKey()
    {
        return 'format';
    }

    public function validate($data)
    {


    }
}