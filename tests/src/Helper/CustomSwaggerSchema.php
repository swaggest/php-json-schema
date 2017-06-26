<?php

namespace Swaggest\JsonSchema\Tests\Helper;


use Swaggest\JsonSchema\Schema as JsonBasicSchema;
use Swaggest\JsonSchema\SwaggerSchema\Schema;
use Swaggest\JsonSchema\SwaggerSchema\SwaggerSchema;

class CustomSwaggerSchema extends SwaggerSchema
{
    public static function setUpProperties($properties, JsonBasicSchema $ownerSchema)
    {
        parent::setUpProperties($properties, $ownerSchema);
        self::$objectItemClassMapping[Schema::className()] = CustomSchema::className();
    }
}