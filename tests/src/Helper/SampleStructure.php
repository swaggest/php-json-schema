<?php

namespace Yaoi\Schema\Tests\Helper;

use Yaoi\Schema\OldSchema;
use Yaoi\Schema\Structure\ClassStructure;
use Yaoi\Schema\Types\IntegerType;
use Yaoi\Schema\Types\StringType;

/**
 * @property $propOne
 * @property $propTwo
 * @property $recursion
 */
class SampleStructure extends ClassStructure
{
    /**
     * @param \Yaoi\Schema\OldConstraint\Properties|static $properties
     * @param OldSchema $schema
     */
    public static function setUpProperties($properties, OldSchema $schema)
    {
        $properties->propOne = StringType::makeSchema();
        $properties->propTwo = IntegerType::makeSchema();
        $properties->recursion = $schema;
    }
}