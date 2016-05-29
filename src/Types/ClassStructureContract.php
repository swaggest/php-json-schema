<?php

namespace Yaoi\Schema\Types;

use Yaoi\Schema\Properties;
use Yaoi\Schema\Structure;

interface ClassStructureContract extends Structure
{
    /**
     * @param $properties static|Properties
     */
    public static function setUpDefinition($properties);
}