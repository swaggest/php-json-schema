<?php

namespace Yaoi\Schema;


class AdditionalProperties extends AbstractConstraint implements Constraint
{
    const SCHEMA_NAME = 'additionalProperties';

    /**
     * @var Schema
     */
    public $parentSchema;

    /**
     * @var Schema
     */
    public $propertiesSchema;

    /**
     * AdditionalProperties constructor.
     * @param $schemaValue
     * @param Schema|null $rootSchema
     */
    public function __construct($schemaValue, Schema $rootSchema = null)
    {
        $this->parentSchema = $rootSchema;
        $this->propertiesSchema = new Schema($schemaValue, $rootSchema);
    }

}