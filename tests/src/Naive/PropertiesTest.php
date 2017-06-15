<?php

namespace Swaggest\JsonSchema\Tests\Naive;

use Swaggest\JsonSchema\Schema;

class PropertiesTest extends \PHPUnit_Framework_TestCase
{
    public function testValid()
    {
        $schema = Schema::import((object)array(
            'properties' => (object)array(
                'one' => (object)array('type' => 'string'),
                'two' => (object)array(),
            )
        ));



        $data = (object)array(
            'one' => 'aaa',
            'two' => 123,
        );
        $entity = $schema->in($data);
        $this->assertSame('aaa', $entity->one);
        $this->assertSame(123, $entity->two);
    }



}