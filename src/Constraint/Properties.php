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
 */
class Properties extends ObjectItem implements Constraint
{
    private $__isReadOnly = false;

    /** @var Schema[] */
    protected $__arrayOfData = array();

    /** @var Schema */
    protected $__schema;

    /**
     * @var Schema[]
     */
    private $__mappedProperties;

    /**
     * @var array
     */
    private $__dataKeyMaps = array();

    /**
     * Data to property mapping, example ["$ref" => "ref"]
     * @var array
     */
    public $__dataToProperty = array();

    /**
     * Property to data mapping, example ["ref" => "$ref"]
     * @var array
     */
    public $__propertyToData = array();

    /**
     * Returns a map of properties by default data name
     * @return Schema[]
     */
    public function &toArray()
    {
        if (!isset($this->__propertyToData[Schema::DEFAULT_MAPPING])) {
            return $this->__arrayOfData;
        }
        if (null === $this->__mappedProperties) {
            $properties = array();
            foreach ($this->__arrayOfData as $propertyName => $property) {
                if (isset($this->__propertyToData[Schema::DEFAULT_MAPPING][$propertyName])) {
                    $propertyName = $this->__propertyToData[Schema::DEFAULT_MAPPING][$propertyName];
                }
                $properties[$propertyName] = $property;
            }
            $this->__mappedProperties = $properties;
        }
        return $this->__mappedProperties;
    }

    /**
     * @param string $mapping
     * @return string[] a map of propertyName to dataName
     */
    public function getDataKeyMap($mapping = Schema::DEFAULT_MAPPING)
    {
        if (!isset($this->__dataKeyMaps[$mapping])) {
            $map = array();
            foreach ($this->__arrayOfData as $propertyName => $property) {
                if (isset($this->__propertyToData[$mapping][$propertyName])) {
                    $map[$propertyName] = $this->__propertyToData[$mapping][$propertyName];
                } else {
                    $map[$propertyName] = $propertyName;
                }
            }
            $this->__dataKeyMaps[$mapping] = $map;
        }

        return $this->__dataKeyMaps[$mapping];
    }

    public function lock()
    {
        $this->__isReadOnly = true;
        return $this;
    }

    public function addPropertyMapping($dataName, $propertyName, $mapping = Schema::DEFAULT_MAPPING)
    {
        $this->__dataToProperty[$mapping][$dataName] = $propertyName;
        $this->__propertyToData[$mapping][$propertyName] = $dataName;
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

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        $result = $this->toArray();

        if ($this->__nestedObjects) {
            foreach ($this->__nestedObjects as $object) {
                foreach ($object->toArray() as $key => $value) {
                    $result[$key] = $value;
                }
            }
        }

        return (object)$result;
    }

}