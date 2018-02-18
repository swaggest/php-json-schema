<?php

namespace Swaggest\JsonSchema\Structure;

use Swaggest\JsonSchema\SchemaContract;

class Nested
{
    /** @var SchemaContract */
    public $schema;
    public function __construct(SchemaContract $schema)
    {
        $this->schema = $schema;
    }
}