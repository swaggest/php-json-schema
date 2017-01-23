<?php

namespace Yaoi\Schema\Tests\Helper;

use Yaoi\Schema\Schema;
use Yaoi\Schema\Structure\ClassStructure;

/**
 * @property $propOne
 * @property $propTwo
 * @property $recursion
 */
class SampleStructure extends ClassStructure
{
    /**
     * @param \Yaoi\Schema\Constraint\Properties|static $properties
     * @param Schema $schema
     */
    public static function setUpProperties($properties, Schema $schema)
    {
        $properties->propOne = Schema::string();
        $properties->propTwo = Schema::integer();
        $properties->recursion = $schema;
    }
}