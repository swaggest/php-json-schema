<?php

namespace Swaggest\JsonSchema\Structure;

use Swaggest\JsonSchema\Base;
use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Constraint\Type;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\OldSchema;

abstract class ClassStructure extends Base implements ClassStructureContract
{
    /**
     * @return Schema
     */
    public static function makeSchema()
    {
        $schema = new Schema();
        $schema->type = new Type(Type::OBJECT);
        $properties = new Properties();
        $schema->properties = $properties;
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
        return static::makeSchema()->import($data);
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Swaggest\JsonSchema\InvalidValue
     */
    public static function export($data)
    {
        return static::makeSchema()->export($data);
    }
}