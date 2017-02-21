<?php

namespace Swaggest\JsonSchema\PreProcessor;


use Swaggest\JsonSchema\DataPreProcessor;
use Swaggest\JsonSchema\Meta\FieldName;
use Swaggest\JsonSchema\Schema;

class NameMapper implements DataPreProcessor
{
    public function process($data, Schema $schema, $import = true)
    {
        if ($schema->properties !== null && is_object($data)) {
            $result = new \stdClass();
            foreach ($schema->properties->toArray() as $propertyName => $property) {
                if ($fieldNameMeta = FieldName::get($property)) {
                    $fieldName = $fieldNameMeta->name;
                } else {
                    $fieldName = $propertyName;
                }

                if ($import) {
                    if (property_exists($data, $fieldName)) {
                        $result->$propertyName = $data->$fieldName;
                    }
                } else {
                    if (property_exists($data, $propertyName)) {
                        $result->$fieldName = $data->$propertyName;
                    }
                }
            }
            return $result;
        }

        return $data;
    }
}