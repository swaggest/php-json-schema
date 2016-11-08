<?php

namespace Yaoi\Schema;

class MagicMap implements \ArrayAccess
{
    protected $_arrayOfData = array();

    public function __set($name, $column)
    {
        $this->_arrayOfData[$name] = $column;
        return $this;
    }

    public function __get($name)
    {
        return $this->_arrayOfData[$name];
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->_arrayOfData);
    }

    public function offsetGet($offset)
    {
        return $this->_arrayOfData[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->__set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        unset($this->_arrayOfData[$offset]);
    }

}