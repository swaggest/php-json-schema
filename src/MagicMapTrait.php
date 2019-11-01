<?php

namespace Swaggest\JsonSchema;

trait MagicMapTrait
{
    protected $__arrayOfData = array();

    /**
     * @param string $name
     * @param mixed $column
     * @return static
     */
    public function __set($name, $column)
    {
        $this->__arrayOfData[$name] = $column;
        return $this;
    }

    public function &__get($name)
    {
        if (isset($this->__arrayOfData[$name])) {
            return $this->__arrayOfData[$name];
        } else {
            $tmp = null;
            return $tmp;
        }
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->__arrayOfData);
    }

    public function &offsetGet($offset)
    {
        if (isset($this->__arrayOfData[$offset])) {
            return $this->__arrayOfData[$offset];
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
        unset($this->__arrayOfData[$offset]);
    }

    public function &toArray()
    {
        return $this->__arrayOfData;
    }

    public function jsonSerialize()
    {
        return (object)$this->__arrayOfData;
    }


    /** @var \ArrayIterator */
    private $iterator;
    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->iterator->current();
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $this->iterator->next();
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->iterator->key();
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return $this->iterator->valid();
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->iterator = new \ArrayIterator($this->__arrayOfData);
    }


    public function __isset($name)
    {
        if (isset($this->__arrayOfData[$name])) {
            return true;
        } else {
            return isset($this->$name);
        }
    }
}
