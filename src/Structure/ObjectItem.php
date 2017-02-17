<?php

namespace Swaggest\JsonSchema\Structure;


use Swaggest\JsonSchema\MagicMap;

class ObjectItem extends MagicMap
{
    protected $__nestedObjects;

    public function setNestedProperty($propertyName, $value, Egg $nestedEgg)
    {
        $nested = &$this->__nestedObjects[$nestedEgg->name];
        if (null === $nested) {
            $nested = $nestedEgg->classSchema->makeObjectItem();
        }
        $nested->$propertyName = $value;
    }

    public function getNested($name)
    {
        if (isset($this->__nestedObjects[$name])) {
            return $this->__nestedObjects[$name];
        }
        return null;
    }

    protected $__additionalPropertyNames;
    public function addAdditionalPropertyName($name)
    {
        $this->__additionalPropertyNames[] = $name;
    }

    /**
     * @return null|string[]
     */
    public function getAdditionalPropertyNames()
    {
        return $this->__additionalPropertyNames;
    }

    protected $__patternPropertyNames;

    public function addPatternPropertyName($pattern, $name)
    {
        $this->__additionalPropertyNames[$pattern][] = $name;
    }

    /**
     * @param $pattern
     * @return null|string[]
     */
    public function getPatternPropertyNames($pattern)
    {
        if (isset($this->__patternPropertyNames[$pattern])) {
            return $this->__patternPropertyNames[$pattern];
        } else {
            return null;
        }
    }

    public function jsonSerialize()
    {
        if ($this->__nestedObjects) {
        } else {
            return (object)$this->__arrayOfData;
        }
    }


}