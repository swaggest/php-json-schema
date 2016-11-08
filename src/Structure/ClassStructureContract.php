<?php

namespace Yaoi\Schema\Structure;

use Yaoi\Schema\OldConstraint\Properties;
use Yaoi\Schema\OldSchema;

interface ClassStructureContract
{
    /**
     * @param Properties|static $properties
     * @param OldSchema $ownerSchema
     */
    public static function setUpProperties($properties, OldSchema $ownerSchema);
    public static function getAdditionalProperties(OldSchema $ownerSchema);
}