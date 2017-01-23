<?php

namespace Yaoi\Schema\Tests\Helper;


use Yaoi\Schema\NG\Schema;
use Yaoi\Schema\Constraint\Properties;
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
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $properties->level3 = Schema::integer();
    }


}