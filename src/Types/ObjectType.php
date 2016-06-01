<?php

namespace Yaoi\Schema\Types;

use Yaoi\Schema\AdditionalProperties;
use Yaoi\Schema\Properties;
use Yaoi\Schema\Transformer;

class ObjectType extends AbstractType implements Transformer
{
    public function import($data)
    {
        if (!is_array($data)) {
            throw new \Exception('Array expected');
        }

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

    public function export($data)
    {
        $result = array();
        $data = (array)$data;
        if ($properties = Properties::getFromSchema($this->parentSchema)) {
            foreach ($properties->properties as $name => $property) {
                if (isset($data[$name])) {
                    $result[$name] = $property->export($data[$name]);
                    unset($data[$name]);
                }
            }
        }

        if ($additionalProperties = AdditionalProperties::getFromSchema($this->parentSchema)) {
            foreach ($data as $name => $value) {
                $result[$name] = $additionalProperties->propertiesSchema->export($value);
                unset($data[$name]);
            }
        }

        if (!empty($data)) {
            throw new \Exception('Unexpected properties: ' . implode(', ', array_keys($data)));
        }

        return $result;
    }


}