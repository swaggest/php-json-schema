<?php

namespace Yaoi\Schema\Types;

use Yaoi\Schema\Constraint;
use Yaoi\Schema\Schema;
use Yaoi\Schema\Transformer;

class StringType implements Transformer,Constraint
{
    public function __construct($schemaValue, Schema $rootSchema = null)
    {
        
    }


    public function import($data)
    {
        if (!is_string($data) && !method_exists($data, '__toString')) {
            throw new \Exception('String required', \Exception::INVALID_VALUE);
        }
        return (string)$data;
    }
}