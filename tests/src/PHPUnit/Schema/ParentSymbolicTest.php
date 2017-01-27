<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Schema;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Constraint\Type;
use Swaggest\JsonSchema\Schema;

class ParentSymbolicTest extends ParentTest
{
    protected function deepSchema()
    {
        $schema = Schema::object()
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