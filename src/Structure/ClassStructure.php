<?php

namespace Yaoi\Schema\Structure;

use Yaoi\Schema\Base;
use Yaoi\Schema\Properties;
use Yaoi\Schema\Schema;
use Yaoi\Schema\Types\ObjectType;

abstract class ClassStructure extends Base implements ClassStructureContract
{
    /**
     * @return Schema
     */
    public static function makeSchema()
    {
        $properties = new Properties();
        static::setUpProperties($properties);
        $schema = new Schema($properties);
        $schema->setConstraint(new ObjectType());
        return $schema;
    }
}