<?php

namespace Yaoi\Schema\Types;


use Yaoi\Schema\Exception;

class BooleanType extends AbstractType
{
    const TYPE = 'boolean';

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
        if (!is_bool($data)) {
            throw new Exception('Boolean required', Exception::INVALID_VALUE);
        }
    }
}