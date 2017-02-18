<?php

namespace Swaggest\JsonSchema\Tests\Helper;

use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;

/**
 * @property string $propOne
 * @property int $propTwo
 * @property $recursion
 */
class SampleStructure extends ClassStructure
{
    public $native;

    /**
     * @param \Swaggest\JsonSchema\Constraint\Properties|static $properties
     * @param Schema $schema
     */
    public static function setUpProperties($properties, Schema $schema)
    {
        $properties->native = Schema::boolean();
        $properties->propOne = Schema::string();
        $properties->propTwo = Schema::integer();
        $properties->recursion = $schema;
    }
}