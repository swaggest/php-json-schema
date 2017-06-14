<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit;


use Swaggest\JsonSchema\SchemaLoader;

class AdditionalPropertiesTest extends \PHPUnit_Framework_TestCase
{

    public function testBasic()
    {
        $schemaData = (object)array(
            'type' => 'object',
            'additionalProperties' => (object)array(
                'type' => 'integer'
            ),
        );

        $schema = \Swaggest\JsonSchema\JsonSchema::importToSchema($schemaData);

        $object = $schema->import(
            (object)array('one' => 1, 'two' => 2)
        );

        $this->assertSame(1, $object->one);
        $this->assertSame(2, $object->two);
    }


    public function testRef()
    {
//        $this->markTestSkipped('Not implemented');
        $schemaData = (object)array(
            'type' => 'object',
            'additionalProperties' => (object)array(
                '$ref' => '#/def'
            ),
            'def' => (object)array(
                'type' => 'integer',
            ),
        );


        $schema = \Swaggest\JsonSchema\JsonSchema::importToSchema($schemaData);
        //print_r($schema);

        $object = $schema->import(
            (object)array('one' => 1, 'two' => 2)
        );

        $this->assertSame(1, $object->one);
        $this->assertSame(2, $object->two);
    }

    public function testWithProperties()
    {

        $schemaData = (object)array(
            'type' => 'object',
            'additionalProperties' => (object)array(
                '$ref' => '#/def'
            ),
            'properties' => (object)array(
                'zero' => (object)array(
                    'type' => 'string',
                ),
            ),
            'def' => (object)array(
                'type' => 'integer',
            ),
        );

        $schema = \Swaggest\JsonSchema\JsonSchema::importToSchema($schemaData);
        $object = $schema->import(
            (object)array('zero' => '0', 'one' => 1, 'two' => 2)
        );

        $this->assertSame('0', $object->zero);
        $this->assertSame(1, $object->one);
        $this->assertSame(2, $object->two);
    }


    public function testNonRootSchema()
    {
        $schemaData = (object)array(
            'type' => 'object',
            'properties' => (object)array(
                'deeper' => (object)array(
                    'type' => 'object',
                    'additionalProperties' => (object)array(
                        'type' => 'integer'
                    ),
                )
            ),
        );

        $schema = \Swaggest\JsonSchema\JsonSchema::importToSchema($schemaData);

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