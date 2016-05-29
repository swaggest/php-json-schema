<?php

namespace Yaoi\Schema;


class Type extends AbstractConstraint implements Transformer, Constraint
{
    const SCHEMA_NAME = 'type';

    public $type;
    public $schema;

    public function __construct($schemaValue, Schema $rootSchema = null)
    {
        $this->type = $schemaValue;
        $this->schema = $rootSchema;
    }

    public function import($data)
    {
        if ($this->type === 'object') {
            $result = new \stdClass();
            if ($properties = Properties::getFromSchema($this->schema)) {
                foreach ($properties->properties as $name => $property) {
                    if (isset($data[$name])) {
                        $result->$name = $property->import($data[$name]);
                        unset($data[$name]);
                    }
                }
            }

            if ($additionalProperties = AdditionalProperties::getFromSchema($this->schema)) {
                foreach ($data as $name => $value) {
                    $result->$name = $additionalProperties->propertiesSchema->import($value);
                    unset($data[$name]);
                }
            }

            if (!empty($data)) {
                throw new \Exception('Unexpected properties: ' . implode(', ', array_keys($data)));
            }

            return $result;
        }
        return $data;
    }


}