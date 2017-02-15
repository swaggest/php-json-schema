<?php

namespace Swaggest\JsonSchema\Constraint;

use Swaggest\JsonSchema\Exception;
use Swaggest\JsonSchema\MagicMap;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\Egg;

/**
 * @method Schema __get($key)
 */
class Properties extends MagicMap implements Constraint
{
    /** @var Schema[] */
    protected $__arrayOfData = array();

    public function __set($name, $column)
    {
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


    /** @var Egg[] */
    private $nestedProperties = array();

    public function addNested(Schema $nested, $name = null)
    {
        if (null === $nested->properties) {
            throw new Exception('Schema with properties required', Exception::PROPERTIES_REQUIRED);
        }
        if (null === $name) {
            $name = $nested->objectItemClass;
        }
        if (null === $name) {
            throw new Exception('Undefined nested name', Exception::UNDEFINED_NESTED_NAME);
        }
        foreach ($nested->properties->toArray() as $propertyName => $property) {
            $this->nestedProperties[$propertyName] = new Egg($nested, $name, $property);
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