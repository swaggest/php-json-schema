<?php

namespace Yaoi\Schema\Tests\Helper;

use Yaoi\Schema\Schema;
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
     * @param \Yaoi\Schema\ObjectFlavour\Properties|static $properties
     * @param Schema $schema
     */
    public static function setUpProperties($properties, Schema $schema)
    {
        $properties->propOne = StringType::makeSchema();
        $properties->propTwo = IntegerType::makeSchema();
        $properties->recursion = $schema;
    }
}