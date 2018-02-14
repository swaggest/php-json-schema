<?php

namespace Swaggest\JsonSchema\Constraint;

use Swaggest\JsonSchema\Exception;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\Egg;
use Swaggest\JsonSchema\Structure\Nested;
use Swaggest\JsonSchema\Structure\ObjectItem;

/**
 * @method Schema __get($key)
 * @method Schema[] toArray()
 */
class Properties extends ObjectItem implements Constraint
{
    /** @var Schema[] */
    protected $__arrayOfData = array();

    /** @var Schema */
    protected $__schema;

    public function setSchema(Schema $schema)
    {
        $this->__schema = $schema;
        return $this;
    }

    public function getSchema()
    {
        return $this->__schema;
    }

    /**
     * @param string $name
     * @param mixed $column
     * @return $this|static
     * @throws Exception
     */
    public function __set($name, $column)
    {
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

    /** @var Schema|null */
    private $additionalProperties;

    /**
     * @param Schema $additionalProperties
     * @return Properties
     */
    public function setAdditionalProperties(Schema $additionalProperties = null)
    {
        $this->additionalProperties = $additionalProperties;
        return $this;
    }


    /** @var Egg[][] */
    public $nestedProperties = array();

    /** @var Schema[] */
    public $nestedPropertyNames = array();

    protected function addNested(Schema $nested, $name)
    {
        if (null === $nested->properties) {
            throw new Exception('Schema with properties required', Exception::PROPERTIES_REQUIRED);
        }
        $this->nestedPropertyNames[$name] = $name;
        foreach ($nested->properties->toArray() as $propertyName => $property) {
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
}