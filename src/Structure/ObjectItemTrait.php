<?php

namespace Swaggest\JsonSchema\Structure;

use Swaggest\JsonSchema\MagicMapTrait;

/**
 * Trait ObjectItemTrait
 * @package Swaggest\JsonSchema\Structure
 * @see ObjectItemContract
 */
trait ObjectItemTrait
{
    use MagicMapTrait;

    /** @var ObjectItem[] */
    protected $__nestedObjects;
    protected $__documentPath;
    protected $__fromRef;

    public function getNestedObject($className) {
        if (isset($this->__nestedObjects[$className])) {
            return $this->__nestedObjects[$className];
        }
        return null;
    }

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
        $this->__patternPropertyNames[$pattern][] = $name;
    }

    /**
     * @param string $pattern
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
                foreach ($object->toArray() as $key => $value) {
                    $result[$key] = $value;
                }
            }
            return (object)$result;
        } else {
            return (object)$this->__arrayOfData;
        }
    }

    /**
     * @return string
     */
    public function getDocumentPath()
    {
        return $this->__documentPath;
    }
    
    public function setDocumentPath($path)
    {
        $this->__documentPath = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getFromRef()
    {
        return $this->__fromRef;
    }

    public function setFromRef($ref)
    {
        $this->__fromRef = $ref;
        return $this;
    }
}