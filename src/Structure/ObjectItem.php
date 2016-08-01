<?php

namespace Yaoi\Schema\Structure;


class ObjectItem
{
    private $unmatchedProperties;
    private $additionalProperties = array();
    private $properties = array();

    public function __construct($unmatchedProperties = array())
    {
        $this->unmatchedProperties = $unmatchedProperties;
    }

    public function getAdditionalProperties()
    {
        return $this->additionalProperties;
    }

    public function getUnmatchedProperties()
    {
        return $this->unmatchedProperties; // todo check for empty array and return new stdClass
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->unmatchedProperties)) {
            return $this->unmatchedProperties[$name];
        } elseif (array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        } elseif (array_key_exists($name, $this->additionalProperties)) {
            return $this->additionalProperties[$name];
        }

        return null;
    }

    public function setProperty($name, $value)
    {
        $this->properties[$name] = $value;
        unset($this->unmatchedProperties[$name]);
    }

    public function setAdditionalProperty($name, $value)
    {
        $this->additionalProperties[$name] = $value;
        unset($this->unmatchedProperties[$name]);
    }

    public function hasUnmatchedPproperties()
    {
        return !empty($this->unmatchedProperties);
    }
}