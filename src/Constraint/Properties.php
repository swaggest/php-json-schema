<?php

namespace Yaoi\Schema\Constraint;

use Yaoi\Schema\AbstractConstraint;
use Yaoi\Schema\Constraint;
use Yaoi\Schema\Exception;
use Yaoi\Schema\Schema;
use Yaoi\Schema\Structure\ObjectItem;

class Properties extends AbstractConstraint
{
    public static function getSchemaKey()
    {
        return 'properties';
    }

    /**
     * @var Schema[]
     */
    public $properties;

    public $className;

    public function __construct($properties = array(), Schema $ownerSchema = null)
    {
        foreach ($properties as $name => $schemaData) {
            $this->properties[$name] = new Schema($schemaData, $ownerSchema);
        }
    }

    public function setOwnerSchema(Schema $ownerSchema)
    {
        $this->ownerSchema = $ownerSchema;
        foreach ($this->properties as $name => $schema) {
            $schema->setParentSchema($ownerSchema, $name);
        }
        return $this;
    }


    public function setProperty($name, $value)
    {
        return $this->__set($name, $value);
    }

    public function __set($name, $value)
    {
        if ($value instanceof Constraint) {
            $value = new Schema($value, $this->ownerSchema);
        } elseif (!$value instanceof Schema) {
            throw new Exception('Constraint or Schema expected', Exception::INVALID_VALUE);
        }

        $this->properties[$name] = $value;
        return $this;
    }

    public function __get($name)
    {
        return isset($this->properties[$name]) ? $this->properties[$name] : null;
    }

    /**
     * @return Schema[]
     */
    public function getArray()
    {
        return $this->properties;
    }


    public function getProperty($name)
    {
        return isset($this->properties[$name])
            ? $this->properties[$name]
            : null;
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasProperty($name)
    {
        return isset($this->properties[$name]);
    }

    /**
     * @param $data
     * @param $result
     * @return bool
     */
    public function importFailed($data, &$result)
    {
        if (!$result instanceof ObjectItem) {
            $result = new ObjectItem();
        }
        foreach ($this->properties as $name => $property) {
            $value = $property->import($data[$name]);
            $result->setProperty($name, $value);
        }


        return false;
    }

    public function exportFailed($entity, &$data)
    {
        // TODO: Implement exportFailed() method.
    }


    public static function getPriority()
    {
        return self::P1;
    }


}