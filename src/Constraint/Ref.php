<?php

namespace Swaggest\JsonSchema\Constraint;

use Swaggest\JsonSchema\Schema;

class Ref implements Constraint
{
    public $ref;
    public function __construct($ref, Schema $schema)
    {
        $this->ref = $ref;
        $this->schema = $schema;
    }

    /** @var Schema */
    private $schema;


    /**
     * @return Schema
     * @throws \Exception
     */
    public function getSchema()
    {
        return $this->schema;
    }
}