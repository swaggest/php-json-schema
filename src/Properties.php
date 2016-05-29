<?php

namespace Yaoi\Schema;


class Properties extends AbstractConstraint implements Constraint
{
    const PROPERTIES = 'properties';
    
    /**
     * @var Schema[]
     */
    public $properties;
    private $rootSchema;
    private $parentSchema;

    public function __construct($data, Schema $rootSchema = null, Schema $parentSchema = null)
    {
        $this->rootSchema = $rootSchema;
        $this->parentSchema = $parentSchema;
        foreach ($data as $name => $schemaData) {
            $this->properties[$name] = new Schema($schemaData, $rootSchema, $parentSchema);
        }
    }
}