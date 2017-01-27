<?php

namespace Yaoi\Schema\Structure;

use Yaoi\Schema\Base;
use Yaoi\Schema\Constraint\Properties;
use Yaoi\Schema\Constraint\Type;
use Yaoi\Schema\Schema;
use Yaoi\Schema\OldSchema;

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
     * @throws \Yaoi\Schema\InvalidValue
     */
    public static function import($data)
    {
        //static $schemas = array();
        return static::makeSchema()->import($data);
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Yaoi\Schema\InvalidValue
     */
    public static function export($data)
    {
        return static::makeSchema()->export($data);
    }
}