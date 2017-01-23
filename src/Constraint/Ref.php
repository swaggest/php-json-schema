<?php

namespace Yaoi\Schema\Constraint;

use Yaoi\Schema\NG\Schema;

class Ref implements Constraint
{
    private $ref;
    public function __construct($ref, Schema $schema)
    {
        $this->ref = $ref;
        $this->schema = $schema;
    }

    public function setToSchema(Schema $schema)
    {
        $schema->ref = $this;
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