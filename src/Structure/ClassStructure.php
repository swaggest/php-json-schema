<?php

namespace Swaggest\JsonSchema\Structure;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Constraint\Type;
use Swaggest\JsonSchema\DataPreProcessor;
use Swaggest\JsonSchema\NameMirror;

abstract class ClassStructure extends ObjectItem implements ClassStructureContract
{
    /**
     * @return ClassSchema
     */
    public static function schema()
    {
        static $schemas = array();
        $className = get_called_class();
        $schema = &$schemas[$className];

        if (null === $schema) {
            $schema = new ClassSchema();
            $properties = new Properties();
            $schema->properties = $properties;
            $schema->objectItemClass = get_called_class();
            static::setUpProperties($properties, $schema);
        }

        return $schema;
    }

    /**
     * @return Properties|static
     */
    public static function properties()
    {
        return static::schema()->properties;
    }

    /**
     * @param $data
     * @param DataPreProcessor $preProcessor
     * @return static
     * @throws \Swaggest\JsonSchema\InvalidValue
     */
    public static function import($data, DataPreProcessor $preProcessor = null)
    {
        return static::schema()->import($data, $preProcessor);
    }

    /**
     * @param $data
     * @param DataPreProcessor $preProcessor
     * @return mixed
     * @throws \Swaggest\JsonSchema\InvalidValue
     */
    public static function export($data, DataPreProcessor $preProcessor = null)
    {
        return static::schema()->export($data, $preProcessor);
    }

    /**
     * @param ObjectItem $objectItem
     * @return static
     */
    public static function pick(ObjectItem $objectItem)
    {
        $className = get_called_class();
        if (isset($objectItem->__nestedObjects[$className])) {
            return $objectItem->__nestedObjects[$className];
        }
        return null;
    }

    /**
     * @return static
     */
    public static function create()
    {
        return new static;
    }

    protected $__validateOnSet = true; // todo skip validation during import

    public function jsonSerialize()
    {
        $result = new \stdClass();
        $properties = static::schema()->properties;
        foreach ($properties->toArray() as $name => $schema) {
            $value = $this->$name;
            if ((null !== $value) || array_key_exists($name, $this->__arrayOfData)) {
                $result->$name = $value;
            }
        }
        foreach ($properties->nestedPropertyNames as $name) {
            /** @var ObjectItem $nested */
            $nested = $this->$name;
            if (null !== $nested) {
                foreach ((array)$nested->jsonSerialize() as $key => $value) {
                    $result->$key = $value;
                }
            }
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

    public function __set($name, $column) // todo nested schemas
    {
        if ($this->__validateOnSet) {
            if ($property = static::schema()->properties[$name]) {
                $property->export($column);
            }
        }
        $this->__arrayOfData[$name] = $column;
        return $this;
    }

    public static function className() {
        return get_called_class();
    }
}