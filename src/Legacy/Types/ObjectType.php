<?php

namespace Yaoi\Schema\Types;

use Yaoi\Schema\OldConstraint\AdditionalProperties;
use Yaoi\Schema\Exception;
use Yaoi\Schema\OldConstraint\Properties;

class ObjectType extends AbstractType
{
    const TYPE = 'object';

    public function import($data)
    {
        if (!is_array($data)) {
            throw new \Exception('Array expected');
        }

        $result = new \stdClass();
        if ($properties = Properties::getFromSchema($this->ownerSchema)) {
            foreach ($properties->properties as $name => $property) {
                if (isset($data[$name])) {
                    try {
                        $result->$name = $property->import($data[$name]);
                    }
                    catch (Exception $exception) {
                        $exception->pushStructureTrace('properties:' . $name);
                        throw $exception;
                    }
                    unset($data[$name]);
                }
            }
        }

        if ($additionalProperties = AdditionalProperties::getFromSchema($this->ownerSchema)) {
            foreach ($data as $name => $value) {
                try {
                    $result->$name = $additionalProperties->propertiesSchema->import($value);
                }
                catch (Exception $exception) {
                    $exception->pushStructureTrace('additionalProperties:' . $name);
                    throw $exception;
                }
                unset($data[$name]);
            }
        }

        if (!empty($data)) {
            throw new \Exception('Unexpected properties: ' . implode(', ', array_keys($data)));
        }

        return $result;
    }

    public function export($entity)
    {
        $result = array();
        $entity = (array)$entity;
        if ($properties = Properties::getFromSchema($this->ownerSchema)) {
            foreach ($properties->properties as $name => $property) {
                if (isset($entity[$name])) {
                    $result[$name] = $property->export($entity[$name]);
                    unset($entity[$name]);
                }
            }
        }

        if ($additionalProperties = AdditionalProperties::getFromSchema($this->ownerSchema)) {
            foreach ($entity as $name => $value) {
                $result[$name] = $additionalProperties->propertiesSchema->export($value);
                unset($entity[$name]);
            }
        }

        if (!empty($entity)) {
            throw new \Exception('Unexpected properties: ' . implode(', ', array_keys($entity)));
        }

        return $result;
    }


}