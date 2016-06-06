<?php

namespace Yaoi\Schema\Tests;


use Yaoi\Schema\Schema;

class RefTest extends \PHPUnit_Framework_TestCase
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


    public function testRootResolve()
    {
        $schemaData = array(
            'type' => 'object',
            'properties' => array(
                'one' => array(
                    '$ref' => '#/definitions/test',
                ),
                'two' => array(
                    '$ref' => '#/definitions/test',
                ),
            ),
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
        $object = $schema->import(array(
                'two' => array(
                    'one' => 123
                )
            )
        );
        $this->assertSame(123, $object->two->one);

    }

}