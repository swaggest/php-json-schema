<?php

namespace Swaggest\JsonSchema\Meta;


abstract class AbstractMeta
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