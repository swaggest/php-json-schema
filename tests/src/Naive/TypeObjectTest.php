<?php

namespace Swaggest\JsonSchema\Tests\Naive;


use Swaggest\JsonSchema\Exception\TypeException;
use Swaggest\JsonSchema\SchemaLoader;

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
        $this->assertSame('123', $schema->import((object)array('aaa' => '123'))->aaa);

        $object = $schema->import((object)array('3.45' => '123'));
        $this->assertSame('123', $object->{3.45});

        $data = $schema->export($object);
        $this->assertSame(array('3.45' => '123'), (array)$data);
    }

    public function testInvalidObject()
    {
//        $this->markTestSkipped('additionalProperties or generic object required, not implemented');

        $this->setExpectedException(get_class(new TypeException()), 'Object required');
        $schema = SchemaLoader::create()->readSchema(array('type' => 'object'));
        $this->assertSame(123, $schema->import(123));
    }
}