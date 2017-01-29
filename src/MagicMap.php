<?php

namespace Swaggest\JsonSchema;

class MagicMap implements \ArrayAccess, \JsonSerializable
{
    protected $_arrayOfData = array();

    public function __set($name, $column)
    {
        $this->_arrayOfData[$name] = $column;
        return $this;
    }

    public function &__get($name)
    {
        if (isset($this->_arrayOfData[$name])) {
            return $this->_arrayOfData[$name];
        } else {
            $tmp = null;
            return $tmp;
        }
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->_arrayOfData);
    }

    public function &offsetGet($offset)
    {
        if (isset($this->_arrayOfData[$offset])) {
            return $this->_arrayOfData[$offset];
        } else {
            $tmp = null;
            return $tmp;
        }
    }

    public function offsetSet($offset, $value)
    {
        $this->__set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        unset($this->_arrayOfData[$offset]);
    }

    public function &toArray()
    {
        return $this->_arrayOfData;
    }

    function jsonSerialize()
    {
        return (object)$this->_arrayOfData;
    }


}