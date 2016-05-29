<?php

namespace Yaoi\Schema;


class Type extends AbstractConstraint implements Transformer, Constraint
{
    const TYPE = 'type';

    const TYPE_OBJECT = 'object';
    const TYPE_STRING = 'string';
    const TYPE_INTEGER = 'integer';

    public $type;
    /** @var Schema */
    public $rootSchema;
    /** @var Schema */
    private $parentSchema;

    public function __construct($schemaValue, Schema $rootSchema = null, Schema $parentSchema = null)
    {
        $this->type = $schemaValue;
        $this->rootSchema = $rootSchema;
        $this->parentSchema = $parentSchema;
    }

    private function importObject($data)
    {
        $result = new \stdClass();
        if ($properties = Properties::getFromSchema($this->parentSchema)) {
            foreach ($properties->properties as $name => $property) {
                if (isset($data[$name])) {
                    $result->$name = $property->import($data[$name]);
                    unset($data[$name]);
                }
            }
        }

        if ($additionalProperties = AdditionalProperties::getFromSchema($this->parentSchema)) {
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

    private function importString($data)
    {
        if (!is_string($data)) {
            throw new \Exception('String expected');
        }
        return $data;
    }

    private function importInteger($data)
    {
        if (!is_int($data)) {
            throw new \Exception('Integer expected');
        }
        return $data;
    }

    public function import($data)
    {
        if ($this->type === self::TYPE_OBJECT) {
            return $this->importObject($data);
        } elseif ($this->type === self::TYPE_STRING) {
            return $this->importString($data);
        } elseif ($this->type === self::TYPE_INTEGER) {
            return $this->importInteger($data);
        }
        return $data;
    }


}