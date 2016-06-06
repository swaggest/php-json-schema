<?php

namespace Yaoi\Schema\Types;

use Yaoi\Schema\Exception;
use Yaoi\Schema\Transformer;

class ArrayType extends AbstractType implements Transformer
{
    const TYPE_ARRAY = 'array';

    public function import($data)
    {
        if (!is_array($data)) {
            throw new Exception('Array expected');
        }
        return $data;
    }

    public function export($data)
    {
        if (!is_array($data)) {
            throw new Exception('Array expected');
        }
        return $data;
    }


}