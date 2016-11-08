<?php

namespace Yaoi\Schema\OldConstraint;

use Yaoi\Schema\AbstractConstraint;

class Format extends AbstractConstraint
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