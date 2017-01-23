<?php

namespace Yaoi\Schema\Tests\Helper;


use Yaoi\Schema\Constraint\Properties;
use Yaoi\Schema\NG\Schema;
use Yaoi\Schema\Structure\ClassStructure;

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
        $properties->level2 = LevelThreeClass::makeSchema();
    }


}