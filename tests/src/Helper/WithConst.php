<?php

namespace Swaggest\JsonSchema\Tests\Helper;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;

class WithConst extends ClassStructure
{
    /** @var string */
    public $foo;

    /**
     * @param Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $properties->foo = Schema::string();
        $properties->foo->const = "abc";

        $ownerSchema->type = Schema::OBJECT;
    }
}