<?php

namespace Yaoi\Schema\OldConstraint;

use Yaoi\Schema\AbstractConstraint;
use Yaoi\Schema\OldSchema;

class Items extends AbstractConstraint
{
    public static function getSchemaKey()
    {
        return 'items';
    }

    /**
     * @var OldSchema
     */
    public $itemsSchema;

    public $isAllowed = true;

    public function __construct($schemaValue, OldSchema $ownerSchema = null)
    {
        if (false === $schemaValue) {
            $this->isAllowed = false;
        } elseif (true === $schemaValue) {
            $this->itemsSchema = new OldSchema(array(), $ownerSchema);
        } else {
            $this->itemsSchema = new OldSchema($schemaValue, $ownerSchema);
        }
    }

    public function setOwnerSchema(OldSchema $ownerSchema)
    {
        $this->ownerSchema = $ownerSchema;
        $this->itemsSchema->setParentSchema($ownerSchema, 'Items');
        return $this;
    }


}