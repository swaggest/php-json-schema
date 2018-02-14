<?php

namespace Swaggest\JsonSchema\Structure;

use Swaggest\JsonSchema\Schema;

class ClassSchema extends Schema
{
    public function nested()
    {
        return new Nested($this);
    }
}