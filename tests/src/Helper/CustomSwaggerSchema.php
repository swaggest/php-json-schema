<?php

namespace Swaggest\JsonSchema\Tests\Helper;


use Swaggest\JsonSchema\Context;
use Swaggest\JsonSchema\SwaggerSchema\Schema;
use Swaggest\JsonSchema\SwaggerSchema\SwaggerSchema;

class CustomSwaggerSchema extends SwaggerSchema
{
    public static function import($data, Context $options = null)
    {
        if ($options === null) {
            $options = new Context();
        }
        $options->objectItemClassMapping[Schema::className()] = CustomSchema::className();
        return parent::import($data, $options);
    }
}