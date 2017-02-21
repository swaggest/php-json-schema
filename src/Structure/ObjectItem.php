<?php

namespace Swaggest\JsonSchema\Structure;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\MagicMap;

class ObjectItem extends MagicMap
{
    /** @var ObjectItem[] */
    protected $__nestedObjects;

    public function setNestedProperty($propertyName, $value, Egg $nestedEgg)
    {
        $nestedName = $nestedEgg->name;
        $nested = &$this->__nestedObjects[$nestedName];
        if (null === $nested) {
            $nested = $nestedEgg->classSchema->makeObjectItem();
            $this->__nestedObjects[$nestedName] = $nested;
            if ($nestedName !== $nestedEgg->classSchema->objectItemClass) {
                $this->$nestedName = $nested;
            }
        }
        $nested->$propertyName = $value;
        $this->__arrayOfData[$propertyName] = &$nested->$propertyName;
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
            $result = $this->__arrayOfData;
            foreach ($this->__nestedObjects as $object) {
                foreach ($object->__arrayOfData as $key => $value) {
                    $result[$key] = $value;
                }
            }
            return (object)$result;
        } else {
            return (object)$this->__arrayOfData;
        }
    }


}