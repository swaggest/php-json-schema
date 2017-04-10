<?php

namespace Swaggest\JsonSchema\SwaggerSchema;



class Util
{
    public static function getHttpMethods()
    {
        $names = PathItem::names();
        return array(
            $names->get,
            $names->put,
            $names->post,
            $names->delete,
            $names->options,
            $names->head,
            $names->patch,
        );
    }

    /**
     * @param BodyParameter|HeaderParameterSubSchema|FormDataParameterSubSchema|QueryParameterSubSchema|PathParameterSubSchema $parameter
     * @return Schema
     */
    public static function parameterToSchema($parameter)
    {
        if ($parameter instanceof BodyParameter) {
            return $parameter->schema;
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

            return Schema::import($schemaData);
        }
    }

}