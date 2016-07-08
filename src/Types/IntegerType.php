<?php

namespace Yaoi\Schema\Types;

use Yaoi\Schema\Exception;

class IntegerType extends AbstractType
{
    const TYPE = 'integer';

    public function import($data)
    {
        $this->validate($data);
        return $data;
    }

    public function export($data)
    {
        $this->validate($data);
        return $data;
    }


    public function validate($data)
    {
        if (!is_int($data)) {
            throw new Exception('Integer required', Exception::INVALID_VALUE);
        }
    }

}