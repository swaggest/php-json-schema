<?php

namespace Yaoi\Schema;

interface Constraint
{
    const KEY = '';

    public function setOwnerSchema(Schema $ownerSchema);
}