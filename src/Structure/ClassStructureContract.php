<?php

namespace Yaoi\Schema\Structure;

use Yaoi\Schema\Constraint\Properties;
use Yaoi\Schema\NG\Schema;

interface ClassStructureContract
{
    /**
     * @param Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema);
}