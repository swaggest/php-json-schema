<?php

namespace Yaoi\Schema\Structure;

use Yaoi\Schema\Schema;

/**
 * Class ClassProperties
 * @package Yaoi\Schema\Structure
 * @deprecated 
 */
class ClassProperties
{

    private $className;

    public function __construct($className = null)
    {
        $this->className = $className;
    }

    /**
     * @var Schema[]
     */
    private $data = array();

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    /**
     * @return Schema[]
     */
    public function getArray()
    {
        return $this->data;
    }

    /**
     * @param $name
     * @return Schema|null
     */
    public function getByName($name)
    {
        return $this->$name;
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasProperty($name)
    {
        return isset($this->data[$name]);
    }
}