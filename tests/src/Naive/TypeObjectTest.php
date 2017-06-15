<?php

namespace Swaggest\JsonSchema\Tests\Naive;

use Swaggest\JsonSchema\Exception\TypeException;
use Swaggest\JsonSchema\Schema;

class TypeObjectTest extends \PHPUnit_Framework_TestCase
{
    public function testValid()
    {
//        $this->markTestSkipped('additionalProperties or generic object required, not implemented');
        $schema = Schema::import(
            (object)array(
                'type' => 'object',
            )
        );
        $this->assertSame('123', $schema->in((object)array('aaa' => '123'))->aaa);

        $object = $schema->in((object)array('3.45' => '123'));
        $this->assertSame('123', $object->{3.45});

        $data = $schema->out($object);
        $this->assertSame(array('3.45' => '123'), (array)$data);
    }

    public function testInvalidObject()
    {
//        $this->markTestSkipped('additionalProperties or generic object required, not implemented');

        $this->setExpectedException(get_class(new TypeException()), 'Object expected, 123 received');
        $schema = Schema::import((object)array('type' => 'object'));
        $this->assertSame(123, $schema->in(123));
    }
}