<?php

namespace Yaoi\Schema;

interface Constraint
{
    public function setOwnerSchema(Schema $ownerSchema);
}