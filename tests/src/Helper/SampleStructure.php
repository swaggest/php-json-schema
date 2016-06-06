<?php

namespace Yaoi\Schema\Tests\Helper;


use Yaoi\Schema\Structure\ClassProperties;
use Yaoi\Schema\Structure\ClassStructure;
use Yaoi\Schema\Types\IntegerType;
use Yaoi\Schema\Types\StringType;

/**
 * @property $propOne
 * @property $propTwo
 */
class SampleStructure extends ClassStructure
{

    /**
     * @param ClassProperties|static $properties
     */
    public static function setUpProperties($properties)
    {
        $properties->propOne = StringType::create();
        $properties->propTwo = IntegerType::create();
    }
}