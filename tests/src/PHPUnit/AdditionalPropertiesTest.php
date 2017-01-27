<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit;


use Swaggest\JsonSchema\SchemaLoader;
use Swaggest\JsonSchema\OldSchema;

class AdditionalPropertiesTest extends \PHPUnit_Framework_TestCase
{

    public function testBasic()
    {
        $schemaData = array(
            'type' => 'object',
            'additionalProperties' => (object)array(
                'type' => 'integer'
            ),
        );

        $schema = SchemaLoader::create()->readSchema($schemaData);

        $object = $schema->import(
            (object)array('one' => 1, 'two' => 2)
        );

        $this->assertSame(1, $object->one);
        $this->assertSame(2, $object->two);
    }


    public function testRef()
    {
//        $this->markTestSkipped('Not implemented');
        $schemaData = array(
            'type' => 'object',
            'additionalProperties' => (object)array(
                '$ref' => '#/def'
            ),
            'def' => (object)array(
                'type' => 'integer',
            ),
        );


        $schema = SchemaLoader::create()->readSchema($schemaData);
        //print_r($schema);

        $object = $schema->import(
            (object)array('one' => 1, 'two' => 2)
        );

        $this->assertSame(1, $object->one);
        $this->assertSame(2, $object->two);
    }

    public function testWithProperties()
    {

        $schemaData = array(
            'type' => 'object',
            'additionalProperties' => (object)array(
                '$ref' => '#/def'
            ),
            'properties' => array(
                'zero' => array(
                    'type' => 'string',
                ),
            ),
            'def' => (object)array(
                'type' => 'integer',
            ),
        );

        $schema = SchemaLoader::create()->readSchema($schemaData);
        $object = $schema->import(
            (object)array('zero' => '0', 'one' => 1, 'two' => 2)
        );

        $this->assertSame('0', $object->zero);
        $this->assertSame(1, $object->one);
        $this->assertSame(2, $object->two);
    }


    public function testNonRootSchema()
    {
        $schemaData = array(
            'type' => 'object',
            'properties' => array(
                'deeper' => (object)array(
                    'type' => 'object',
                    'additionalProperties' => (object)array(
                        'type' => 'integer'
                    ),
                )
            ),
        );

        $schema = SchemaLoader::create()->readSchema($schemaData);

        //print_r($schema);

        $object = $schema->import(
            (object)array(
                'deeper' => (object)array('one' => 1, 'two' => 2)
            )
        );

        $this->assertSame(1, $object->deeper->one);
        $this->assertSame(2, $object->deeper->two);


    }


}