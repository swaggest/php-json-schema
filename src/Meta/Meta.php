<?php

namespace Swaggest\JsonSchema\Meta;

interface Meta
{
    /**
     * @param MetaHolder $schema
     * @return static
     */
    public static function get(MetaHolder $schema);
}