<?php

namespace Yaoi\Schema\Constraint;

use Yaoi\Schema\NG\SchemaLoader;
use Yaoi\Schema\NG\Schema;

class Ref implements Constraint
{
    public static function getConstraintName()
    {
        return '$ref';
    }

    private $ref;
    private $rootSchema;
    public function __construct($ref, Schema $rootSchema)
    {
        $this->ref = $ref;
        $this->rootSchema = $rootSchema;
    }

    /** @var Schema */
    private $schema;


    /**
     * @return Schema
     * @throws \Exception
     */
    public function getSchema()
    {
        if (null === $this->schema) {
            $this->schema = $this->rootSchema->getDefinition($this->ref);
        }
        return $this->schema;
    }
}