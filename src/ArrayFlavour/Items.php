<?php

namespace Yaoi\Schema\ArrayFlavour;

use Yaoi\Schema\AbstractConstraint;
use Yaoi\Schema\Schema;
use Yaoi\Schema\Schematic;

class Items extends AbstractConstraint implements Schematic
{
    public static function getSchemaKey()
    {
        return 'items';
    }

    /**
     * @var Schema
     */
    public $itemsSchema;

    public $isAllowed = true;

    public function __construct($schemaValue, Schema $ownerSchema = null)
    {
        if (false === $schemaValue) {
            $this->isAllowed = false;
        } elseif (true === $schemaValue) {
            $this->itemsSchema = new Schema(array(), $ownerSchema);
        } else {
            $this->itemsSchema = new Schema($schemaValue, $ownerSchema);
        }
    }

    public function setOwnerSchema(Schema $ownerSchema)
    {
        $this->ownerSchema = $ownerSchema;
        $this->itemsSchema->setParentSchema($ownerSchema, 'Items');
        return $this;
    }


}