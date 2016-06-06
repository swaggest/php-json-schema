<?php

namespace Yaoi\Schema\Structure;

interface ClassStructureContract
{
    /**
     * @param ClassProperties|static $properties
     */
    public static function setUpProperties($properties);
}