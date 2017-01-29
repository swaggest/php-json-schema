<?php

namespace Swaggest\JsonSchema\Structure;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Constraint\Type;
use Swaggest\JsonSchema\NameMirror;
use Swaggest\JsonSchema\Schema;

abstract class ClassStructure extends ObjectItem implements ClassStructureContract
{
    /**
     * @return Schema
     */
    public static function schema()
    {
        $schema = new Schema();
        $schema->type = new Type(Type::OBJECT);
        $properties = new Properties();
        $schema->properties = $properties;
        $schema->objectItemClass = get_called_class();
        static::setUpProperties($properties, $schema);
        return $schema;
    }

    /**
     * @param $data
     * @return static
     * @throws \Swaggest\JsonSchema\InvalidValue
     */
    public static function import($data)
    {
        //static $schemas = array();
        return static::schema()->import($data);
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Swaggest\JsonSchema\InvalidValue
     */
    public static function export($data)
    {
        return static::schema()->export($data);
    }

    /**
     * @return static
     */
    static function create()
    {
        return new static;
    }

    protected $__hasNativeProperties = true;
    protected $__validateOnSet = true;

    public function jsonSerialize()
    {
        if ($this->__hasNativeProperties) {
            $result = new \stdClass();
            foreach (static::schema()->properties->toArray() as $name => $schema) {
                $value = $this->$name;
                if (null !== $value || array_key_exists($name, $this->_arrayOfData)) {
                    $result->$name = $value;
                }
            }
        } else {
            $result = parent::jsonSerialize();
        }

        return $result;
    }

    /**
     * @return static
     */
    public static function names()
    {
        static $nameflector = null;
        if (null === $nameflector) {
            $nameflector = new NameMirror();
        }
        return $nameflector;
    }

    public function __set($name, $column)
    {
        if ($this->__validateOnSet) {
            if ($property = static::schema()->properties[$name]) {
                $property->export($column);
            }
        }
        $this->_arrayOfData[$name] = $column;
        return $this;
    }


}