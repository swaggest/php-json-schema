<?php

namespace Yaoi\Schema\Tests\Helper;


use Yaoi\Schema\OldConstraint\Properties;
use Yaoi\Schema\OldSchema;
use Yaoi\Schema\Structure\ClassStructure;
use Yaoi\Schema\Types\IntegerType;

class LevelThreeClass extends ClassStructure
{
    /**
     * @var int
     */
    public $level3;

    /**
     * @param Properties|static $properties
     * @param OldSchema $ownerSchema
     */
    public static function setUpProperties($properties, OldSchema $ownerSchema)
    {
        $properties->level3 = IntegerType::makeSchema();
    }


}