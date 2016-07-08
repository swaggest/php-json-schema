<?php
namespace Yaoi\Schema\Types;

use Yaoi\Schema\Exception;

class StringType extends AbstractType
{
    const TYPE = 'string';

    public function import($data)
    {
        // TODO: Implement import() method.
    }

    public function export($data)
    {
        // TODO: Implement export() method.
    }

    protected function validate($data)
    {
        if (!is_string($data)) {
            throw new Exception("String required", Exception::INVALID_VALUE);
        }
        
        
    }
}