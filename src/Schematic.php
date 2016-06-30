<?php

namespace Yaoi\Schema;

interface Schematic extends Constraint
{
    public function __construct($schemaValue, Schema $ownerSchema = null);
}