<?php

namespace Yaoi\Schema;

interface Constraint
{
    public static function getSchemaKey();
    public function setOwnerSchema(Schema $ownerSchema);
}