<?php

namespace Yaoi\Schema\Tests\Schema;


use Yaoi\Schema\Constraint\Properties;
use Yaoi\Schema\Types\IntegerType;
use Yaoi\Schema\Types\ObjectType;

class ParentSymbolicTest extends ParentTest
{
    protected function deepSchema()
    {
        $schema = ObjectType::makeSchema(
            Properties::create()
                ->setProperty(
                    'level1',
                    ObjectType::makeSchema(
                        Properties::create()
                            ->setProperty(
                                'level2',
                                ObjectType::makeSchema(
                                    Properties::create()
                                        ->setProperty(
                                            'level3',
                                            IntegerType::makeSchema()
                                        )
                                )
                            )
                    )
                )
        );
        return $schema;
    }

}