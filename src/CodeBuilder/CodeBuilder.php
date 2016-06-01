<?php

namespace Yaoi\Schema\CodeBuilder;

use Yaoi\Schema\Schema;
use Yaoi\Schema\Types\StringType;

class CodeBuilder
{
    public function getInstantiationCode(Schema $schema)
    {
        
    }

    public function getTypeHint(Schema $schema)
    {
        if (StringType::getFromSchema($schema)) {
            
        }

    }

    public function getPhpDoc(Schema $schema)
    {
        
    }


}