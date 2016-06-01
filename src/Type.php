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
    const TYPE = 'type';

    const TYPE_OBJECT = 'object';
    const TYPE_STRING = 'string';
    const TYPE_INTEGER = 'integer';
    const TYPE_NUMBER = 'number';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_ARRAY = 'array';

    public static function factory($schemaValue, Schema $rootSchema = null, Schema $parentSchema = null)
    {
        if (is_array($schemaValue)) {
            
        }
        
        switch ($schemaValue) {
            case self::TYPE_OBJECT:
                return new ObjectType($schemaValue, $rootSchema, $parentSchema);
            case self::TYPE_STRING:
                return new StringType($schemaValue, $rootSchema, $parentSchema);
            case self::TYPE_INTEGER:
                return new IntegerType($schemaValue, $rootSchema, $parentSchema);
            case self::TYPE_NUMBER:
                return new NumberType($schemaValue, $rootSchema, $parentSchema);
            case self::TYPE_BOOLEAN:
                return new BooleanType($schemaValue, $rootSchema, $parentSchema);
            case self::TYPE_ARRAY:
                return new ArrayType($schemaValue, $rootSchema, $parentSchema);
            default:
                throw new Exception('Unknown type ' . $schemaValue);
        }
    }


}