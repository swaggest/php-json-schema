<?php

namespace Yaoi\Schema\Structure;

use Yaoi\Schema\Base;
use Yaoi\Schema\ObjectFlavour\Properties;
use Yaoi\Schema\Schema;
use Yaoi\Schema\Types\ObjectType;

abstract class ClassStructure extends Base implements ClassStructureContract
{
    /**
     * @return Schema
     */
    public static function makeSchema()
    {
        $schema = new Schema();
        $properties = new Properties();
        static::setUpProperties($properties, $schema);
        $schema->setConstraint(new ObjectType());
        $schema->setConstraint($properties);
        return $schema;
    }
}