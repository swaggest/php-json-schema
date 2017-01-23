<?php

namespace Yaoi\Schema\Constraint;


use Yaoi\Schema\NG\MagicMap;
use Yaoi\Schema\NG\Schema;

class Definitions extends MagicMap implements Constraint
{
    /** @var Schema[] */
    protected $_arrayOfData = array();

    public function setToSchema(Schema $schema)
    {
        $schema->definitions = $this;
    }
}