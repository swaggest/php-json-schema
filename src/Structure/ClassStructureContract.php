<?php

namespace Yaoi\Schema\Structure;

use Yaoi\Schema\Properties;

interface ClassStructureContract
{
    /**
     * @param Properties|static $properties
     */
    public static function setUpProperties($properties);
}