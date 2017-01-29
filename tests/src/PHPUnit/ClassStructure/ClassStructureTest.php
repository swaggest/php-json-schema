<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\ClassStructure;


use Swaggest\JsonSchema\Exception\TypeException;
use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\Tests\Helper\SampleStructure;

class ClassStructureTest extends \PHPUnit_Framework_TestCase
{
    public function testSample()
    {
        $schema = SampleStructure::schema();

        $object = $schema->import((object)array(
            'propOne' => '1',
            'propTwo' => 2,
            'recursion' => (object)array(
                'propOne' => '11',
                'propTwo' => 22,
            )
        ));

        $this->assertInstanceOf(get_class(new SampleStructure()), $object);

        $this->assertSame('1', $object->propOne);
        $this->assertSame(2, $object->propTwo);

        $this->assertSame('11', $object->recursion->propOne);
        $this->assertSame(22, $object->recursion->propTwo);
    }


    public function testSampleInvalid()
    {
        $schema = SampleStructure::schema();
        $this->setExpectedException(get_class(new TypeException()), 'String expected, 11 received at #->properties:recursion->properties:propOne');
        $schema->import((object)array(
            'propOne' => '1',
            'propTwo' => 2,
            'recursion' => (object)array(
                'propOne' => 11,
                'propTwo' => 22,
            )
        ));
    }


}