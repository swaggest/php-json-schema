<?php

namespace Yaoi\Schema;

interface Constraint extends Transformer
{
    public function __construct($schemaValue, Schema $ownerSchema = null);
    public static function getSchemaKey();
    public function setOwnerSchema(Schema $ownerSchema);
}