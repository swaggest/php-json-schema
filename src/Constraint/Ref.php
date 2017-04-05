<?php

namespace Swaggest\JsonSchema\Constraint;

use Swaggest\JsonSchema\Schema;

class Ref implements Constraint
{
    public $ref;
    public function __construct($ref, Schema $schema = null)
    {
        $this->ref = $ref;
        $this->schema = $schema;
    }

    /** @var Schema */
    private $schema;

    /**
     * @param Schema $schema
     * @return Ref
     */
    public function setSchema($schema)
    {
        $this->schema = $schema;
        return $this;
    }

    /**
     * @return Schema
     * @throws \Exception
     */
    public function getSchema()
    {
        return $this->schema;
    }
}