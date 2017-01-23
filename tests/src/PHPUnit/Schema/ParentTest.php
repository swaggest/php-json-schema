<?php

namespace Yaoi\Schema\Tests\PHPUnit\Schema;


use Yaoi\Schema\Exception;
use Yaoi\Schema\NG\Schema;
use Yaoi\Schema\NG\SchemaLoader;
use Yaoi\Schema\OldConstraint\Properties;
use Yaoi\Schema\OldSchema;

class ParentTest extends \PHPUnit_Framework_TestCase
{
    protected function deepSchema()
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

        $schema = SchemaLoader::create()->readSchema($schemaValue);
        return $schema;
    }

    private function assertSchema(Schema $schema)
    {
        $level3Schema = $schema->properties->__get('level1')
            ->properties->__get('level2')
            ->properties->__get('level3');

        $this->assertSame($level3Schema->type->types, array('integer'));
    }

    public function testParent()
    {
        $this->assertSchema($this->deepSchema());
    }


    public function testInvalidImport()
    {
        $schema = $this->deepSchema();
        $this->setExpectedException(get_class(new Exception()), 'Integer required at properties:level1->properties:level2->properties:level3',
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
            $this->assertSame('Integer required at properties:level1->properties:level2->properties:level3',
                $exception->getMessage());
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