<?php

namespace Yaoi\Schema;

class InvalidValue extends \Exception
{
    private $path;

    public function addPath($path)
    {
        $this->message .= ' at ' . $path;
    }

    public function getPath()
    {
        return $this->path;
    }

    const INVALID_VALUE = 1;
    const NOT_IMPLEMENTED = 2;
}