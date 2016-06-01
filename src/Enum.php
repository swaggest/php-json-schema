<?php

namespace Yaoi\Schema;

class Enum extends AbstractConstraint implements Validator, Constraint
{
    private $values;
    
    public function __construct($schemaValue, Schema $rootSchema, Schema $parentSchema)
    {
        
        
    }
    
    public function isValid($data)
    {
        // TODO: Implement isValid() method.
    }
}