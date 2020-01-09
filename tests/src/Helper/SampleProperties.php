<?php

namespace Swaggest\JsonSchema\Tests\Helper;

use Swaggest\JsonSchema\Helper;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Exception\StringException;
use Swaggest\JsonSchema\Structure\ClassStructure;


/**
 * @property string $propOne
 * @property int $propTwo
 * @property $recursion
 */
class SampleProperties extends ClassStructure
{
    const X_PROPERTY_PATTERN = '^x-';

    /**
     * @param \Swaggest\JsonSchema\Constraint\Properties|static $properties
     * @param Schema $schema
     */
    public static function setUpProperties($properties, Schema $schema)
    {
        $schema->type = Schema::OBJECT;
        $schema->additionalProperties = Schema::object();
        $schema->setPatternProperty('^x-', Schema::string());
    }

    /**
     * @return array
     * @codeCoverageIgnoreStart
     */
    public function getAdditionalPropertyValues()
    {
        $result = array();
        if (!$names = $this->getAdditionalPropertyNames()) {
            return $result;
        }
        foreach ($names as $name) {
            $result[$name] = $this->$name;
        }
        return $result;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param string $name
     * @param mixed $value
     * @return self
     * @codeCoverageIgnoreStart
     */
    public function setAdditionalPropertyValue($name, $value)
    {
        $this->addAdditionalPropertyName($name);
        $this->{$name} = $value;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @return string[]
     * @codeCoverageIgnoreStart
     */
    public function getXValues()
    {
        $result = array();
        if (!$names = $this->getPatternPropertyNames(self::X_PROPERTY_PATTERN)) {
            return $result;
        }
        foreach ($names as $name) {
            $result[$name] = $this->$name;
        }
        return $result;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param string $name
     * @param string $value
     * @return self
     * @throws InvalidValue
     * @codeCoverageIgnoreStart
     */
    public function setXValue($name, $value)
    {
        if (!preg_match(Helper::toPregPattern(self::X_PROPERTY_PATTERN), $name)) {
            throw new StringException('Pattern mismatch', StringException::PATTERN_MISMATCH);
        }
        $this->addPatternPropertyName(self::X_PROPERTY_PATTERN, $name);
        $this->{$name} = $value;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @return string
     * @codeCoverageIgnoreStart
     */
    public function getLastName()
    {
        return $this->lastName;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param string $lastName
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }
}
