<?php

namespace Yaoi\Schema\Tests\ClassStructure;


use Yaoi\Schema\Tests\Helper\SampleStructure;

class ClassStructureTest extends \PHPUnit_Framework_TestCase
{
    public function testSample()
    {
        return;
        $schema = SampleStructure::makeSchema();
        $object = $schema->import();
    }
    
    

}