<?php

namespace Swaggest\JsonSchema\Tests\Helper;


use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;

class DeepRefTitle extends ClassStructure
{
    /**
     * @param Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $ownerSchema->setFromRef('http://json-schema.org/draft-04/schema#/properties/title');
        $ownerSchema->setFromRef('#/definitions/title');
    }
}