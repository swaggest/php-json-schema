<?php

namespace Swaggest\JsonSchema\Structure;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\NameMirror;
use Swaggest\JsonSchema\ProcessingOptions;
use Swaggest\JsonSchema\Schema;

abstract class SchemaStructure extends Schema implements ClassStructureContract
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
     * @param $data
     * @param ProcessingOptions $options
     * @return static
     * @deprecated ?
     */
    public static function importToSchema($data, ProcessingOptions $options = null)
    {
        return static::schema()->import($data, $options);
    }

    /**
     * @param $data
     * @param ProcessingOptions $options
     * @return mixed
     * @deprecated ?
     */
    public static function exportFromSchema($data, ProcessingOptions $options = null)
    {
        return static::schema()->export($data, $options);
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

}