<?php

namespace Swaggest\JsonSchema\Tests\Helper;


use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;

class StructureWithItems extends ClassStructure
{
    /** @var LevelThreeClass[] */
    public $list;

    /**
     * @param \Swaggest\JsonSchema\Constraint\Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $properties->list = Schema::create();
        $properties->list->items = LevelThreeClass::schema();
    }


}