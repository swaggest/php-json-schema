<?php
namespace Yaoi\Schema\Types;

use Yaoi\Schema\Exception;

class StringType extends AbstractType
{
    const TYPE = 'string';

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

    protected function validate($data)
    {
        if (!is_string($data)) {
            throw new Exception("String required", Exception::INVALID_VALUE);
        }
        
        
    }
}