<?php

namespace Yaoi\Schema\Constraint;

use Yaoi\Schema\AbstractConstraint;
use Yaoi\Schema\Schema;

class AdditionalProperties extends AbstractConstraint
{
    public static function getSchemaKey()
    {
        return 'additionalProperties';
    }

    /**
     * @var Schema
     */
    public $propertiesSchema;

    public $isAllowed = true;

    public function __construct($schemaValue, Schema $ownerSchema = null)
    {
        if (false === $schemaValue) {
            $this->isAllowed = false;
        } elseif (true === $schemaValue) {
            $this->propertiesSchema = new Schema(array(), $ownerSchema);
        } else {
            $this->propertiesSchema = new Schema($schemaValue, $ownerSchema);
        }
    }

    public function setOwnerSchema(Schema $ownerSchema)
    {
        $this->ownerSchema = $ownerSchema;
        $this->propertiesSchema->setParentSchema($ownerSchema, 'AdditionalProperties');
        return $this;
    }


}