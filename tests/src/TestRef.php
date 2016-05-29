<?php

namespace Yaoi\Schema\Tests;


use Yaoi\Schema\Schema;

class TestRef extends \PHPUnit_Framework_TestCase
{

    public function testResolve()
    {
        $schemaData = array(
            '$ref' => '#/definitions/test',
            'definitions' => array(
                'test' => array(
                    'type' => 'object',
                    'properties' => array(
                        'one' => array(
                            'type' => 'integer'
                        ),
                    ),
                ),
            ),
        );

        $schema = new Schema($schemaData);
        $this->assertSame(123, $schema->import(array('one' => 123))->one);
    }

}