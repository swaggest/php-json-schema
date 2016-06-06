<?php

namespace Yaoi\Schema;


class AdditionalProperties extends AbstractConstraint implements Constraint
{
    const KEY = 'additionalProperties';

    /**
     * @var Schema
     */
    public $propertiesSchema;

    public function __construct($schemaValue, Schema $ownerSchema = null)
    {
        $this->propertiesSchema = new Schema($schemaValue, $ownerSchema);
    }

}