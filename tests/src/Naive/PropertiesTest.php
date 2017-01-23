<?php

namespace Yaoi\Schema\Tests\Naive;


use Yaoi\Schema\SchemaLoader;

class PropertiesTest extends \PHPUnit_Framework_TestCase
{
    public function testValid()
    {
        $schema = SchemaLoader::create()->readSchema(array(
            'properties' => array(
                'one' => array('type' => 'string'),
                'two' => array(),
            )
        ));

        $data = array(
            'one' => 'aaa',
            'two' => 123,
        );
        $entity = $schema->import($data);
        $this->assertSame('aaa', $entity->one);
        $this->assertSame(123, $entity->two);
    }



}