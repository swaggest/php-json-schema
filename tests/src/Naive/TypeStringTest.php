<?php

namespace Swaggest\JsonSchema\Tests\Naive;


use Swaggest\JsonSchema\Exception\TypeException;
use Swaggest\JsonSchema\SchemaLoader;

class TypeStringTest extends \PHPUnit_Framework_TestCase
{
    public function testValid()
    {
        $schema = SchemaLoader::create()->readSchema(
            array(
                'type' => 'string',
            )
        );
        $this->assertSame('123', $schema->import('123'));
    }

    public function testInvalidInteger()
    {
        $schema = SchemaLoader::create()->readSchema(
            array(
                'type' => 'integer',
            )
        );
        $this->setExpectedException(get_class(new TypeException()), 'Integer expected, "123" received');
        $this->assertSame(123, $schema->import('123'));
    }
}