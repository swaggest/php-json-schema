<?php

namespace Yaoi\Schema;


abstract class AbstractFlavour extends AbstractConstraint implements Flavour
{
    public $value;

    public function __construct($schemaValue, Schema $ownerSchema = null)
    {
        $this->value = $schemaValue;
    }

}