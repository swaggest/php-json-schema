<?php

namespace Yaoi\Schema\Constraint;

use Yaoi\Schema\NG\Schema;

interface Constraint
{
    public function setToSchema(Schema $schema);

}