<?php


namespace Swaggest\JsonSchema\Tests\Helper;


use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;

class ClassWithAllOf extends ClassStructure
{
    public $myProperty;

    /**
     * @param Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $properties->myProperty = Schema::string();

        $not = new Schema();
        $not->not = Schema::integer();
        $ownerSchema->allOf[0] = $not;
    }


}