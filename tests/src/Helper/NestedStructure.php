<?php

namespace Swaggest\JsonSchema\Tests\Helper;


use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;

/**
 * @property $ownMagicInt
 */
class NestedStructure extends ClassStructure
{
    public $ownString;

    /**
     * @param Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $properties->ownMagicInt = Schema::integer();
        $properties->ownString = Schema::string();

        $properties->addNested(SampleStructure::schema());
    }
}