<?php

namespace Yaoi\Schema\Structure;

use Yaoi\Schema\Base;
use Yaoi\Schema\Schema;

abstract class ClassStructure extends Base implements ClassStructureContract
{
    /**
     * @return static|ClassProperties
     */
    public static function properties()
    {
        static $propertiesStorage = array();

        $className = get_called_class();
        $properties = &$propertiesStorage[$className];
        if (null !== $properties) {
            return $properties;
        }
        $properties = new ClassProperties($className);
        static::setUpProperties($properties);
        return $properties;
    }

    public static function makeSchema()
    {
        $schema = new Schema();
        

    }
}