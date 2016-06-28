<?php

namespace Yaoi\Schema\Tests\Schema;


use Yaoi\Schema\Tests\Helper\LevelOneClass;

class ParentFixedTest extends ParentTest
{
    protected function deepSchema()
    {
        $schema = LevelOneClass::makeSchema();
        return $schema;
    }

}