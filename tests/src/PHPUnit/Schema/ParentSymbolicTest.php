<?php

namespace Yaoi\Schema\Tests\PHPUnit\Schema;

use Yaoi\Schema\Constraint\Properties;
use Yaoi\Schema\Constraint\Type;
use Yaoi\Schema\Schema;

class ParentSymbolicTest extends ParentTest
{
    protected function deepSchema()
    {
        $schema = Schema::create()
            ->setType(new Type(Type::OBJECT))
            ->setProperties(
                Properties::create()->__set(
                    'level1',
                    Schema::create()->setProperties(Properties::create()->__set(
                        'level2',
                        Schema::create()->setProperties(Properties::create()->__set(
                            'level3',
                            Schema::integer()
                        )))))
            );

        return $schema;
    }

}