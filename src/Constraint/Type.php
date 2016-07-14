<?php

namespace Yaoi\Schema\Constraint;

use Yaoi\Schema\AbstractConstraint;
use Yaoi\Schema\Exception;
use Yaoi\Schema\Schema;
use Yaoi\Schema\Types\AbstractType;
use Yaoi\Schema\Types\ArrayType;
use Yaoi\Schema\Types\BooleanType;
use Yaoi\Schema\Types\IntegerType;
use Yaoi\Schema\Types\NumberType;
use Yaoi\Schema\Types\ObjectType;
use Yaoi\Schema\Types\StringType;

class Type extends AbstractConstraint
{
    public static function getSchemaKey()
    {
        return 'type';
    }

    public function __construct($schemaValue, Schema $ownerSchema = null)
    {
        $this->ownerSchema = $ownerSchema;
        if (!is_array($schemaValue)) {
            $schemaValue = array($schemaValue);
        }
        foreach ($schemaValue as $type) {
            $this->typeHandlers[$type] = self::factory($type, $ownerSchema);
        }
    }

    /**
     * @var AbstractType[]
     */
    private $typeHandlers = array();

    public function import($data)
    {
        $lastException = null;
        foreach ($this->typeHandlers as $typeHandler) {
            try {
                return $typeHandler->import($data);
            }
            catch (Exception $exception) {
                $lastException = $exception;
            }
        }
        throw $lastException;
    }

    public function export($data)
    {
        $lastException = null;
        foreach ($this->typeHandlers as $typeHandler) {
            try {
                return $typeHandler->export($data);
            } catch (Exception $exception) {
                $lastException = $exception;
            }
        }
        throw $lastException;
    }


    private static function factory($schemaValue, Schema $parentSchema = null)
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

    public function getHandlerByType($type) {
        if (isset($this->typeHandlers[$type])) {
            return $this->typeHandlers[$type];
        }
        return null;
    }
    
    

}