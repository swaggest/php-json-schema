<?php

namespace Yaoi\Schema;


class Properties extends AbstractConstraint implements Constraint
{
    const SCHEMA_NAME = 'properties';
    
    /**
     * @var Schema[]
     */
    public $properties;

    public $schema;
    public function __construct($data, Schema $rootSchema = null)
    {
        $this->schema = $rootSchema;
        foreach ($data as $name => $schemaData) {
            $this->properties[$name] = new Schema($schemaData);
        }
    }
}