<?php

namespace Yaoi\Schema\Tests\ClassStructure;


use Yaoi\Schema\Exception;
use Yaoi\Schema\Tests\Helper\SampleStructure;

class ClassStructureTest extends \PHPUnit_Framework_TestCase
{
    public function testSample()
    {
        $schema = SampleStructure::makeSchema();
        //print_r($schema);
        $object = $schema->import(array(
            'propOne' => '1',
            'propTwo' => 2,
            'recursion' => array(
                'propOne' => '11',
                'propTwo' => 22,
            )
        ));

        $this->assertSame('1', $object->propOne);
        $this->assertSame(2, $object->propTwo);

        $this->assertSame('11', $object->recursion->propOne);
        $this->assertSame(22, $object->recursion->propTwo);
    }


    public function testSampleInvalid()
    {
        $schema = SampleStructure::makeSchema();
        $this->setExpectedException(get_class(new Exception()), 'Validation failed');
        $object = $schema->import(array(
            'propOne' => '1',
            'propTwo' => 2,
            'recursion' => array(
                'propOne' => 11,
                'propTwo' => 22,
            )
        ));
    }


}