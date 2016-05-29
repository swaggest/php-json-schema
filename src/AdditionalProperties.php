<?php

namespace Yaoi\Schema;


class AdditionalProperties extends AbstractConstraint implements Constraint
{
    const ADDITIONAL_PROPERTIES = 'additionalProperties';

    /**
     * @var Schema
     */
    private $rootSchema;

    /** @var  Schema */
    private $parentSchema;

    /**
     * @var Schema
     */
    public $propertiesSchema;

    public function __construct($schemaValue, Schema $rootSchema = null, Schema $parentSchema = null)
    {
        $this->rootSchema = $rootSchema;
        $this->propertiesSchema = new Schema($schemaValue, $rootSchema, $parentSchema);
    }

}