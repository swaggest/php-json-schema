<?php

namespace Yaoi\Schema\Types;

use Yaoi\Schema\Base;
use Yaoi\Schema\Exception;

class ObjectItem extends Base
{
    private $structure;
    private $properties;
    private $skipAccessors;

    public function __construct(ObjectType $structure)
    {
        $this->structure = $structure;
        $this->skipAccessors = $structure->isSkipAccessors();
        $this->properties = $structure->getProperties();
    }

    public function __set($name, $value)
    {
        if ($this->skipAccessors) {
            $this->$name = $value;
            return;
        }

        $property = $this->properties->getByName($name);
        if (null === $property) {
            throw new Exception('Setting unknown property ' . $name, Exception::UNKNOWN_PROPERTY);
        }
        try {
            $property->export($value); // validate value by trying an export
        } catch (Exception $exception) {
            throw $this->structure->propagateException($name, $exception);
        }
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        if ($this->skipAccessors) {
            $this->$name = null; // @todo can cause memory leak
            return null;
        }

        if (isset($this->data[$name])) {
            return $this->data[$name];
        } else {
            return null;
        }
    }

    protected $data;

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function getData()
    {
        return $this->skipAccessors ? (array)$this : $this->data;
    }

}