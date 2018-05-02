<?php

namespace Swaggest\JsonSchema\Constraint;

use Swaggest\JsonSchema\Exception;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\SchemaContract;
use Swaggest\JsonSchema\Structure\Egg;
use Swaggest\JsonSchema\Structure\Nested;
use Swaggest\JsonSchema\Structure\ObjectItem;

/**
 * @method SchemaContract __get($key)
 * @method Schema[] toArray()
 */
class Properties extends ObjectItem implements Constraint
{
    private $__isReadOnly = false;

    /** @var Schema[] */
    protected $__arrayOfData = array();

    /** @var Schema */
    protected $__schema;

    /**
     * Property to data mapping, example ["ref" => "$ref"]
     * @var array
     */
    public $__defaultMapping = array();

    public function lock()
    {
        $this->__isReadOnly = true;
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $column
     * @return $this|static
     * @throws Exception
     */
    public function __set($name, $column)
    {
        if ($this->__isReadOnly) {
            throw new Exception('Trying to modify read-only Properties');
        }
        if ($column instanceof Nested) {
            $this->addNested($column->schema, $name);
            return $this;
        }
        parent::__set($name, $column);
        return $this;
    }

    public static function create()
    {
        return new static;
    }

    /** @var Egg[][] */
    public $nestedProperties = array();

    /** @var string[] */
    public $nestedPropertyNames = array();

    /**
     * @param SchemaContract $nested
     * @param string $name
     * @return $this
     * @throws Exception
     */
    protected function addNested(SchemaContract $nested, $name)
    {
        if ($this->__isReadOnly) {
            throw new Exception('Trying to modify read-only Properties');
        }
        if (null === $nested->getProperties()) {
            throw new Exception('Schema with properties required', Exception::PROPERTIES_REQUIRED);
        }
        $this->nestedPropertyNames[$name] = $name;
        foreach ($nested->getProperties()->toArray() as $propertyName => $property) {
            $this->nestedProperties[$propertyName][] = new Egg($nested, $name, $property);
        }
        return $this;
    }

    /**
     * @return Egg[][]
     */
    public function getNestedProperties()
    {
        return $this->nestedProperties;
    }

    public function isEmpty()
    {
        return (count($this->__arrayOfData) + count($this->nestedProperties)) === 0;
    }

    public function jsonSerialize()
    {
        $result = $this->__arrayOfData;
        if ($this->__nestedObjects) {
            foreach ($this->__nestedObjects as $object) {
                foreach ($object->toArray() as $key => $value) {
                    $result[$key] = $value;
                }
            }
        }

        if (isset($this->__defaultMapping)) {
            $mappedResult = new \stdClass();
            foreach ($result as $key => $value) {
                if (isset($this->__defaultMapping[$key])) {
                    $mappedResult->{$this->__defaultMapping[$key]} = $value;
                } else {
                    $mappedResult->$key = $value;
                }
            }
            return $mappedResult;
        }

        return (object)$result;
    }

}