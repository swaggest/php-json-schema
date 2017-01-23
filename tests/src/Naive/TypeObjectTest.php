<?php

namespace Yaoi\Schema\Tests\Naive;


use Yaoi\Schema\Exception;
use Yaoi\Schema\SchemaLoader;
use Yaoi\Schema\OldSchema;

class TypeObjectTest extends \PHPUnit_Framework_TestCase
{
    public function testValid()
    {
//        $this->markTestSkipped('additionalProperties or generic object required, not implemented');
        $schema = SchemaLoader::create()->readSchema(
            array(
                'type' => 'object',
            )
        );
        $this->assertSame('123', $schema->import(array('aaa' => '123'))->aaa);

        $object = $schema->import(array('3.45' => '123'));
        $this->assertSame('123', $object->{3.45});

        $data = $schema->export($object);
        $this->assertSame(array('3.45' => '123'), $data);
    }

    public function testInvalidObject()
    {
//        $this->markTestSkipped('additionalProperties or generic object required, not implemented');

        $this->setExpectedException(get_class(new Exception()), 'Object required', Exception::INVALID_VALUE);
        $schema = SchemaLoader::create()->readSchema(array('type' => 'object'));
        $this->assertSame(123, $schema->import(123));
    }
}