<?php

namespace Yaoi\Schema\Tests\Schema;


use Yaoi\Schema\Exception;
use Yaoi\Schema\ObjectFlavour\Properties;
use Yaoi\Schema\Schema;

class ParentTest extends \PHPUnit_Framework_TestCase
{
    private function deepSchema()
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
        return $schema;
    }

    public function testParent()
    {
        $schema = $this->deepSchema();

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


    public function testInvalidImport()
    {
        $schema = $this->deepSchema();
        $this->setExpectedException(get_class(new Exception()), 'Validation failed (level1->level2->level3)',
            Exception::INVALID_VALUE);
        try {
            $object = $schema->import(array(
                'level1'=> array(
                    'level2' =>array(
                        'level3' => 'abc' // integer required
                    ),
                ),
            ));
        }
        catch (Exception $exception) {
            $this->assertSame(array('level1', 'level2', 'level3'), $exception->getStructureTrace());
            throw $exception;
        }
        //$this->assertSame('abc', $object->level1->level2->level3);
    }

    public function testImport()
    {
        $object = $this->deepSchema()->import(array(
            'level1'=> array(
                'level2' =>array(
                    'level3' => 123 // integer required
                ),
            ),
        ));
        $this->assertSame(123, $object->level1->level2->level3);
    }
}