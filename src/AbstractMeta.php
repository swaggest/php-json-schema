<?php

namespace Swaggest\JsonSchema;

use Swaggest\JsonSchema\Meta\Meta;
use Swaggest\JsonSchema\Meta\MetaHolder;

abstract class AbstractMeta implements Meta
{
    /**
     * @param MetaHolder $schema
     * @return static
     */
    public static function get(MetaHolder $schema)
    {
        return $schema->getMeta(get_called_class());
    }
}