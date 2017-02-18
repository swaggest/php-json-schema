<?php

namespace Swaggest\JsonSchema;

class InvalidValue extends Exception
{
    public function addPath($path)
    {
        $this->message .= ' at ' . $path;
    }

    const INVALID_VALUE = 1;
    const NOT_IMPLEMENTED = 2;
}