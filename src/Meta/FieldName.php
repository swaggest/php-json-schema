<?php

namespace Swaggest\JsonSchema\Meta;

use Swaggest\JsonSchema\Meta;

class FieldName extends Meta
{
    public $name;
    public function __construct($name)
    {
        $this->name = $name;
    }

}