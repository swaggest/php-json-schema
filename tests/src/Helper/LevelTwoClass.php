<?php

namespace Swaggest\JsonSchema\Tests\Helper;


use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;

class LevelTwoClass extends ClassStructure
{
    /**
     * @var LevelThreeClass
     */
    public $level2;

    /**
     * @param Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $properties->level2 = LevelThreeClass::schema();
        $ownerSchema->setFromRef(false);
    }


}