<?php

namespace Yaoi\Schema\Structure;

use Yaoi\Schema\Base;
use Yaoi\Schema\OldConstraint\Properties;
use Yaoi\Schema\OldSchema;
use Yaoi\Schema\OldConstraint\Type;
use Yaoi\Schema\Types\ObjectType;

abstract class ClassStructure extends Base implements ClassStructureContract
{
    /**
     * @return OldSchema
     */
    public static function makeSchema()
    {
        $schema = new OldSchema();
        $properties = new Properties();
        static::setUpProperties($properties, $schema);
        $schema->setConstraint(new Type(ObjectType::TYPE, $schema));
        $schema->setConstraint($properties);
        return $schema;
    }

    /**
     * @param $data
     * @return static
     * @throws \Yaoi\Schema\Exception
     */
    public static function import($data)
    {
        static $schemas = array();
        return static::makeSchema()->import($data);
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Yaoi\Schema\Exception
     */
    public static function export($data)
    {
        return static::makeSchema()->export($data);
    }

    public static function getAdditionalProperties(OldSchema $ownerSchema)
    {
        return null;
    }


}