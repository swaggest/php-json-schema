<?php

namespace Swaggest\JsonSchema;

class NameMirror
{
    public function __get($name)
    {
        return $name;
    }

    public function __set($name, $value)
    {
        throw new \Exception('Unexpected write to read-only structure');
    }
}