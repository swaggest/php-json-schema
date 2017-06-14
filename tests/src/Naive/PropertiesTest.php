<?php

namespace Swaggest\JsonSchema\Tests\Naive;


use Swaggest\JsonSchema\SchemaLoader;

class PropertiesTest extends \PHPUnit_Framework_TestCase
{
    public function testValid()
    {
        $schema = \Swaggest\JsonSchema\JsonSchema::importToSchema((object)array(
            'properties' => (object)array(
                'one' => (object)array('type' => 'string'),
                'two' => (object)array(),
            )
        ));



        $data = (object)array(
            'one' => 'aaa',
            'two' => 123,
        );
        $entity = $schema->import($data);
        $this->assertSame('aaa', $entity->one);
        $this->assertSame(123, $entity->two);
    }



}