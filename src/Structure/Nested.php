<?php

namespace Swaggest\JsonSchema\Structure;

use Swaggest\JsonSchema\Schema;

class Nested
{
    /** @var Schema */
    public $schema;
    public function __construct(Schema $schema)
    {
        $this->schema = $schema;
    }
}