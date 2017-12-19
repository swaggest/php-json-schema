<?php

namespace Swaggest\JsonSchema\Util;

use Swaggest\JsonSchema\Schema as JsonBasicSchema;
use Swaggest\JsonSchema\SwaggerSchema\Schema;

class SwaggerSchemaAdapter extends JsonBasicSchema
{
    public static function convert(Schema $schema)
    {
        return JsonBasicSchema::import(Schema::export($schema));
    }
}