<?php

namespace Yaoi\Schema;


use Yaoi\Schema\Types\ArrayType;
use Yaoi\Schema\Types\BooleanType;
use Yaoi\Schema\Types\IntegerType;
use Yaoi\Schema\Types\NumberType;
use Yaoi\Schema\Types\ObjectType;
use Yaoi\Schema\Types\StringType;

class Type
{
    const KEY = 'type';

    public static function factory($schemaValue, Schema $parentSchema = null)
    {
        if (is_array($schemaValue)) {
            throw new Exception("Please implement me", Exception::NOT_IMPLEMENTED);
        }
        
        switch ($schemaValue) {
            case ObjectType::TYPE_OBJECT:
                return new ObjectType($parentSchema);
            case StringType::TYPE_STRING:
                return new StringType($parentSchema);
            case IntegerType::TYPE_INTEGER:
                return new IntegerType($parentSchema);
            case NumberType::TYPE_NUMBER:
                return new NumberType($parentSchema);
            case BooleanType::TYPE_BOOLEAN:
                return new BooleanType($parentSchema);
            case ArrayType::TYPE_ARRAY:
                return new ArrayType($parentSchema);
            default:
                throw new Exception('Unknown type ' . $schemaValue);
        }
    }


}