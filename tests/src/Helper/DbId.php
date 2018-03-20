<?php

namespace Swaggest\JsonSchema\Tests\Helper;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Meta\AbstractMeta;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructureContract;
use Swaggest\JsonSchema\Structure\ClassStructureTrait;

class DbId extends AbstractMeta implements ClassStructureContract
{
    use ClassStructureTrait;

    /** @var DbTable */
    public $table;

    /**
     * @param Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $properties->table = DbTable::schema();
    }


    public function __construct(DbTable $table)
    {
        $this->table = $table;
    }

}