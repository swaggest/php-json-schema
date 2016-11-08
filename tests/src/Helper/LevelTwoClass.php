<?php

namespace Yaoi\Schema\Tests\Helper;


use Yaoi\Schema\OldConstraint\Properties;
use Yaoi\Schema\OldSchema;
use Yaoi\Schema\Structure\ClassStructure;

class LevelTwoClass extends ClassStructure
{
    /**
     * @var LevelThreeClass
     */
    public $level2;

    /**
     * @param Properties|static $properties
     * @param OldSchema $ownerSchema
     */
    public static function setUpProperties($properties, OldSchema $ownerSchema)
    {
        $properties->level2 = LevelThreeClass::makeSchema();
    }


}