<?php

namespace Yaoi\Schema;


class Items extends AbstractConstraint implements Constraint
{
    const KEY = 'items';

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


}