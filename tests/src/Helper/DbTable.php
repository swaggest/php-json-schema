<?php

namespace Swaggest\JsonSchema\Tests\Helper;

use Swaggest\JsonSchema\AbstractMeta;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructureContract;
use Swaggest\JsonSchema\Structure\ClassStructureTrait;

class DbTable extends AbstractMeta implements ClassStructureContract
{
    use ClassStructureTrait;

    public $tableName;

    /**
     * @param \Swaggest\JsonSchema\Constraint\Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $properties->tableName = Schema::string();
    }
}