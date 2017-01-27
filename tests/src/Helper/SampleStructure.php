<?php

namespace Swaggest\JsonSchema\Tests\Helper;

use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;

/**
 * @property $propOne
 * @property $propTwo
 * @property $recursion
 */
class SampleStructure extends ClassStructure
{
    /**
     * @param \Swaggest\JsonSchema\Constraint\Properties|static $properties
     * @param Schema $schema
     */
    public static function setUpProperties($properties, Schema $schema)
    {
        $properties->propOne = Schema::string();
        $properties->propTwo = Schema::integer();
        $properties->recursion = $schema;
    }
}