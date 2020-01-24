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

    /** @var ObjectItemContract[] */
    protected $__nestedObjects;
    protected $__documentPath;
    /** @var null|string[] */
    protected $__fromRef;

    protected $__hasResolvedValue = false;
    protected $__resolvedValue;

    public function setResolvedValue($value)
    {
        $this->__hasResolvedValue = true;
        $this->__resolvedValue = $value;
        return $this;
    }

    public function getResolvedValue()
    {
        return $this->__resolvedValue;
    }

    public function hasResolvedValue()
    {
        return $this->__hasResolvedValue;
    }

    public function getNestedObject($className)
    {
        if (isset($this->__nestedObjects[$className])) {
            return $this->__nestedObjects[$className];
        }
        return null;
    }

    public function setNestedProperty($propertyName, $value, Egg $nestedEgg)
    {
        $nestedName = $nestedEgg->name;
        /** @var null $nested */
        $nested = &$this->__nestedObjects[$nestedName];
        if (null === $nested) {
            $nested = $nestedEgg->classSchema->makeObjectItem();
            $this->__nestedObjects[$nestedName] = $nested;
            if ($nestedName !== $nestedEgg->classSchema->getObjectItemClass()) {
                $this->$nestedName = $nested;
            }
        }
        $nested->$propertyName = $value;
        $this->__arrayOfData[$propertyName] = &$nested->$propertyName;
    }

    protected $__additionalPropertyNames;

    public function addAdditionalPropertyName($name)
    {
        $this->__additionalPropertyNames[$name] = true;
    }

    /**
     * @return string[]
     */
    public function getAdditionalPropertyNames()
    {
        if (null === $this->__additionalPropertyNames) {
            return [];
        }
        return array_keys($this->__additionalPropertyNames);
    }

    /**
     * @var string[][]
     */
    protected $__patternPropertyNames;

    public function addPatternPropertyName($pattern, $name)
    {
        $this->__patternPropertyNames[$pattern][$name] = true;
    }

    /**
     * @param string $pattern
     * @return null|string[]
     */
    public function getPatternPropertyNames($pattern)
    {
        if (isset($this->__patternPropertyNames[$pattern])) {
            return array_keys($this->__patternPropertyNames[$pattern]);
        }
        return null;
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
     * @return string|null
     * @deprecated use ObjectItemContract::getFromRefs
     * @see ObjectItemContract::getFromRefs
     * @todo remove
     * @see ObjectItemContract::getFromRef
     */
    public function getFromRef()
    {
        return null === $this->__fromRef ? null : $this->__fromRef[0];
    }

    /**
     * @return string[]|null
     * @see ObjectItemContract::getFromRef
     */
    public function getFromRefs()
    {
        return $this->__fromRef;
    }

    /**
     * @param false|string $ref
     * @return $this
     * @see ObjectItemContract::setFromRef
     */
    public function setFromRef($ref)
    {
        if (null === $this->__fromRef) {
            $this->__fromRef = array($ref);
        } else {
            if (false !== $this->__fromRef[0]) {
                $this->__fromRef[] = $ref;
            }
        }
        return $this;
    }

    private $__refPath;

    protected function getFromRefPath()
    {
        if ($this->__refPath === null) {
            $this->__refPath = '';
            if ($this->__fromRef) {
                foreach ($this->__fromRef as $ref) {
                    if ($ref) {
                        $this->__refPath = '->$ref[' . strtr($ref, array('~' => '~1', ':' => '~2')) . ']' . $this->__refPath;
                    }
                }
            }
        }
        return $this->__refPath;
    }
}
