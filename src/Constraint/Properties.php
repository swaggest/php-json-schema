<?php

namespace Swaggest\JsonSchema\Constraint;

use Swaggest\JsonSchema\Exception;
use Swaggest\JsonSchema\MagicMap;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\Egg;
use Swaggest\JsonSchema\Structure\Nested;

/**
 * @method Schema __get($key)
 * @method Schema[] toArray()
 */
class Properties extends MagicMap implements Constraint
{
    /** @var Schema[] */
    protected $__arrayOfData = array();

    public function __set($name, $column)
    {
        if ($column instanceof Nested) {
            $this->addNested($column->schema, $name);
            return $this;
        }
        return parent::__set($name, $column);
    }

    public static function create()
    {
        return new static;
    }

    /** @var Schema */
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
    private $nestedProperties = array();

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
     * @return Egg[]
     */
    public function getNestedProperties()
    {
        return $this->nestedProperties;
    }
}