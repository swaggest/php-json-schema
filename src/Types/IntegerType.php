<?php

namespace Yaoi\Schema\Types;

use Yaoi\Schema\Exception;

class IntegerType extends NumberType
{
    const TYPE = 'integer';

    public function import($data)
    {
        $this->validate($data);
        return $data;
    }

    public function export($entity)
    {
        $this->validate($entity);
        return $entity;
    }


    public function validate($data)
    {
        if (!is_int($data)) {
            throw new Exception('Integer required', Exception::INVALID_VALUE);
        }

        $this->validateFlavours($data);
    }

}