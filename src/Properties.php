<?php

namespace Yaoi\Schema;


class Properties extends AbstractConstraint implements Constraint
{
    const KEY = 'properties';

    /**
     * @var Schema[]
     */
    public $properties;

    public function __construct($properties, Schema $ownerSchema)
    {
        foreach ($properties as $name => $schemaData) {
            $this->properties[$name] = new Schema($schemaData, $ownerSchema);
        }
    }

    public function getProperty($name)
    {
        return isset($this->properties[$name])
            ? $this->properties[$name]
            : null;
    }
}