<?php

namespace Swaggest\JsonSchema;

class NameMirror
{
    /**
     * NameMirror constructor.
     * @param null|string[] $mapping a map of propertyName to dataName
     */
    public function __construct($mapping = null)
    {
        $this->mapping = $mapping;
    }

    private $mapping;

    public function __get($name)
    {
        if ($this->mapping !== null && isset($this->mapping[$name])) {
            return $this->mapping[$name];
        }

        return $name;
    }

    public function __set($name, $value)
    {
        throw new \Exception('Unexpected write to read-only structure');
    }
}