<?php

namespace Yaoi\Schema\Tests\PHPUnit;


use Yaoi\Schema\SchemaLoader;

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

        $loader = new SchemaLoader();
        $schema = $loader->readSchema($schemaData);

        $import = $schema->import((object)array('one' => 123));
        $this->assertSame(123, $import->one);
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


        $loader = new SchemaLoader();
        $schema = $loader->readSchema($schemaData);

        $object = $schema->import((object)array(
                'two' => (object)array(
                    'one' => 123
                )
            )
        );
        $this->assertSame(123, $object->two->one);

    }

}