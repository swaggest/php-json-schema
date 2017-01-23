<?php

namespace Yaoi\Schema\Constraint;

use Yaoi\Schema\Schema;

interface Constraint
{
    /**
     * @param Schema $schema
     * @return mixed
     * @todo justify existence
     */
    public function setToSchema(Schema $schema);

}