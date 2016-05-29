<?php

namespace Yaoi\Schema;


class Ref extends AbstractConstraint implements Transformer, Constraint
{
    const SCHEMA_NAME = '$ref';

    public $ref;

    /** @var Schema */
    public $rootSchema;

    /** @var Schema */
    public $constraintSchema;
    
    public function __construct($schemaValue, Schema $rootSchema = null)
    {
        $this->ref = $schemaValue;
        $this->rootSchema = $rootSchema;

        if ($this->ref === '#') {
            $this->constraintSchema = $rootSchema;
        }

        if ($this->ref[0] === '#') {
            $path = explode('/', trim($this->ref, '#/'));
            var_dump($path);
        }
    }

    public function setSchema(Schema $schema)
    {
        $this->rootSchema = $schema;
        return $this;
    }

    public function import($data)
    {
        return $data;
    }


}