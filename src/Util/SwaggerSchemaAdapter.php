<?php

namespace Swaggest\JsonSchema\Util;

use Swaggest\JsonSchema\Schema as JsonBasicSchema;
use Swaggest\JsonSchema\SwaggerSchema\BodyParameter;
use Swaggest\JsonSchema\SwaggerSchema\FormDataParameterSubSchema;
use Swaggest\JsonSchema\SwaggerSchema\HeaderParameterSubSchema;
use Swaggest\JsonSchema\SwaggerSchema\PathParameterSubSchema;
use Swaggest\JsonSchema\SwaggerSchema\QueryParameterSubSchema;
use Swaggest\JsonSchema\SwaggerSchema\Schema;

class SwaggerSchemaAdapter extends JsonBasicSchema
{
    public static function convert(Schema $schema)
    {
        return JsonBasicSchema::import(Schema::export($schema));
    }

    /**
     * @param BodyParameter|HeaderParameterSubSchema|FormDataParameterSubSchema|QueryParameterSubSchema|PathParameterSubSchema $parameter
     * @return JsonBasicSchema
     * @throws \Exception
     */
    public function parameterToJsonSchema($parameter)
    {
        if ($parameter instanceof BodyParameter) {
            return $parameter->schema;
        } elseif ($parameter instanceof Schema) {
            try {
                return JsonBasicSchema::import(json_decode(json_encode($parameter)));
            } catch (\Exception $e) {
                print_r($parameter);
                throw $e;
            }
        } else {
            /** @var BodyParameter|HeaderParameterSubSchema|FormDataParameterSubSchema|QueryParameterSubSchema|PathParameterSubSchema $schemaData */
            $schemaData = $parameter->export($parameter);
            if (isset($schemaData->required) && !is_array($schemaData->required)) {
                unset($schemaData->required);
            }
            if (isset($schemaData->in)) {
                unset($schemaData->in);
            }
            if (isset($schemaData->name)) {
                unset($schemaData->name);
            }
            if (isset($schemaData->collectionFormat)) {
                unset($schemaData->collectionFormat);
            }


            return JsonBasicSchema::import($schemaData);
        }
    }

}