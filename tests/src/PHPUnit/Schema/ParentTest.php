<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Schema;


use Swaggest\JsonSchema\Exception\TypeException;
use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\SchemaLoader;

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
        $this->setExpectedException(get_class(new TypeException()),
            'Integer expected, "abc" received at #->properties:level1->properties:level2->properties:level3');
        try {
            $object = $schema->import((object)array(
                'level1'=> (object)array(
                    'level2' =>(object)array(
                        'level3' => 'abc' // integer required
                    ),
                ),
            ));
        }
        catch (InvalidValue $exception) {
            $this->assertSame('Integer expected, "abc" received at #->properties:level1->properties:level2->properties:level3',
                $exception->getMessage());
            throw $exception;
        }
        //$this->assertSame('abc', $object->level1->level2->level3);
    }

    public function testImport()
    {
        $object = $this->deepSchema()->import((object)array(
            'level1'=> (object)array(
                'level2' => (object)array(
                    'level3' => 123 // integer required
                ),
            ),
        ));
        $this->assertSame(123, $object->level1->level2->level3);
    }
}