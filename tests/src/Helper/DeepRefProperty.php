<?php

namespace Swaggest\JsonSchema\Tests\Helper;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;

class DeepRefProperty extends ClassStructure
{
    /**
     * @param Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $ownerSchema->type = Schema::OBJECT;
        $ownerSchema->setFromRef('#/definitions/lvlA');
        $ownerSchema->setFromRef('#/definitions/lvlB');
        $ownerSchema->setFromRef('#/definitions/lvlC');
        $ownerSchema->setFromRef('#/definitions/lvlD');
    }


}