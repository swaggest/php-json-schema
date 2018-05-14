<?php

namespace Swaggest\JsonSchema\Tests\Helper;


use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;

class DeepRefRoot extends ClassStructure
{
    public $directTitle;

    public $intermediateTitle;

    public $prop;

    /**
     * @param Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $properties->prop = DeepRefProperty::schema();

        $properties->directTitle = new Schema();
        $properties->directTitle->ref = 'http://json-schema.org/draft-04/schema#/properties/title';

        $properties->intermediateTitle = DeepRefTitle::schema();

        $ownerSchema->type = Schema::STRING;
    }
}