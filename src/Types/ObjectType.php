<?php

namespace Yaoi\Schema\Types;


use Yaoi\Schema\AbstractStructure;
use Yaoi\Schema\Exception;
use Yaoi\Schema\Properties;
use Yaoi\Schema\Structure;
use Yaoi\Schema\TypeConstraint;

class ObjectType implements TypeConstraint
{
    /** @var Properties */
    protected $properties; // @todo move properties to a separate constraint

    public function __construct()
    {
        $this->properties = new Properties();
    }

    public function setProperty($name, Structure $structure)
    {
        $this->properties->$name = $structure;
        return $this;
    }

    /**
     * @return Properties
     */
    public function getProperties()
    {
        return $this->properties;
    }


    protected $skipAccessors = false;

    public function setSkipAccessors($skipAccessors = true)
    {
        $this->skipAccessors = $skipAccessors;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isSkipAccessors()
    {
        return $this->skipAccessors;
    }

    protected function innerImport($data)
    {
        if (!is_array($data)) {
            throw new Exception('Array expected', Exception::INVALID_VALUE);
        }

        $instance = new ObjectItem($this);

        $values = array();
        foreach ($this->properties->getArray() as $propertyName => $property) {
            $name = $property->getDataKeyName();
            $value = isset($data[$name]) ? $data[$name] : null;

            try {
                $value = $property->import($value);
            } catch (Exception $exception) {
                throw $this->propagateException($name, $exception);
            }

            if ($this->skipAccessors) {
                $instance->$propertyName = $value;
            } else {
                if ($value !== null) {
                    $values[$propertyName] = $value;
                }
            }
        }
        if ($values) {
            $instance->setData($values);
        }
        return $instance;
    }

    protected function innerExport($data)
    {
        if (!$data instanceof ObjectItem) {
            throw new Exception(ObjectItem::className() . ' expected', Exception::INVALID_VALUE);
        }

        $result = array();
        $values = $data->getData();
        foreach ($this->properties->getArray() as $propertyName => $property) {
            $name = $property->getDataKeyName();
            $value = isset($values[$propertyName]) ? $values[$propertyName] : null;

            try {
                $value = $property->export($value);
            } catch (Exception $exception) {
                throw $this->propagateException($name, $exception);
            }

            if (null !== $value) {
                $result[$name] = $value;
            }
        }
        return $result;

    }
}