<?php

namespace Yaoi\Schema\Tests\ClassStructure;


use Yaoi\Schema\Tests\Helper\SampleStructure;

class ClassStructureTest extends \PHPUnit_Framework_TestCase
{
    public function testSample()
    {
        $schema = SampleStructure::makeSchema();
        print_r($schema);
        $object = $schema->import(array(
            'propOne' => '1',
            'propTwo' => 2,
        ));
        print_r($object);
    }
    
    

}