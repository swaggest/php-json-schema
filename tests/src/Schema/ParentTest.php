<?php

namespace Yaoi\Schema\Tests\Schema;


use Yaoi\Schema\Properties;
use Yaoi\Schema\Schema;

class ParentTest extends \PHPUnit_Framework_TestCase
{
    public function testParent()
    {
        $schemaValue = array(
            'type' => 'object',
            'properties' => array(
                'level1' => array(
                    'type' => 'object',
                    'properties' => array(
                        'level2' => array(
                            'type' => 'object',
                            'properties' => array(
                                'level3' => array(
                                    'type' => 'integer',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        );
        $schema = new Schema($schemaValue);

        $level1Schema = Properties::getFromSchema($schema)->getProperty('level1');
        $level2Schema = Properties::getFromSchema($level1Schema)->getProperty('level2');
        $level3Schema = Properties::getFromSchema($level2Schema)->getProperty('level3');

        $this->assertSame($schema, $level1Schema->getRootSchema());
        $this->assertSame($schema, $level2Schema->getRootSchema());
        $this->assertSame($schema, $level3Schema->getRootSchema());

        $this->assertSame($schema, $level1Schema->getParentSchema());
        $this->assertSame($level1Schema, $level2Schema->getParentSchema());
        $this->assertSame($level2Schema, $level3Schema->getParentSchema());
    }


    public function testAttach()
    {
        
    }

}