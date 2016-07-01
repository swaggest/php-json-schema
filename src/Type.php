<?php

namespace Yaoi\Schema;


use Yaoi\Schema\Types\AbstractType;
use Yaoi\Schema\Types\ArrayType;
use Yaoi\Schema\Types\BooleanType;
use Yaoi\Schema\Types\IntegerType;
use Yaoi\Schema\Types\NumberType;
use Yaoi\Schema\Types\ObjectType;
use Yaoi\Schema\Types\StringType;

class Type extends AbstractConstraint implements Transformer
{
    public static function getSchemaKey()
    {
        return 'type';
    }

    public function __construct($schemaValue, Schema $ownerSchema = null)
    {
        $this->ownerSchema = $ownerSchema;
        $this->typeHandler = self::factory($schemaValue, $ownerSchema);
    }

    /**
     * @var AbstractType
     */
    private $typeHandler;

    public function import($data)
    {
        return $this->typeHandler->import($data);
    }

    public function export($data)
    {
        return $this->typeHandler->export($data);
    }


    public static function factory($schemaValue, Schema $parentSchema = null)
    {
        if (is_array($schemaValue)) {
            throw new Exception("Please implement me", Exception::NOT_IMPLEMENTED);
        }
        
        switch ($schemaValue) {
            case ObjectType::TYPE:
                return new ObjectType($parentSchema);
            case StringType::TYPE:
                return new StringType($parentSchema);
            case IntegerType::TYPE:
                return new IntegerType($parentSchema);
            case NumberType::TYPE:
                return new NumberType($parentSchema);
            case BooleanType::TYPE:
                return new BooleanType($parentSchema);
            case ArrayType::TYPE:
                return new ArrayType($parentSchema);
            default:
                throw new Exception('Unknown type ' . $schemaValue);
        }
    }

    
    

}