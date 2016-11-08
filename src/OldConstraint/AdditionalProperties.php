<?php

namespace Yaoi\Schema\OldConstraint;

use Yaoi\Schema\AbstractConstraint;
use Yaoi\Schema\OldSchema;

class AdditionalProperties extends AbstractConstraint
{
    public static function getSchemaKey()
    {
        return 'additionalProperties';
    }

    /**
     * @var OldSchema
     */
    public $propertiesSchema;

    public $isAllowed = true;

    public function __construct($schemaValue, OldSchema $ownerSchema = null)
    {
        if (false === $schemaValue) {
            $this->isAllowed = false;
        } elseif (true === $schemaValue) {
            $this->propertiesSchema = new OldSchema(array(), $ownerSchema);
        } else {
            $this->propertiesSchema = new OldSchema($schemaValue, $ownerSchema);
        }
    }

    public function setOwnerSchema(OldSchema $ownerSchema)
    {
        $this->ownerSchema = $ownerSchema;
        $this->propertiesSchema->setParentSchema($ownerSchema, 'AdditionalProperties');
        return $this;
    }


}