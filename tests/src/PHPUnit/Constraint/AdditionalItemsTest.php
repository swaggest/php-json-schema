<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Constraint;


use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\JsonSchema;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\SchemaLoader;

class AdditionalItemsTest extends \PHPUnit_Framework_TestCase
{
    public function testAdditionalItemsAreNotAllowed()
    {
        $schema = JsonSchema::importToSchema(
            (object)array(
                'items' => array(
                    new \stdClass(),
                    new \stdClass(),
                    new \stdClass(),
                ),
                'additionalItems' => false,
            )
        );

        $this->setExpectedException(get_class(new InvalidValue()));
        $schema->import(array(1,2,3,4));
    }

    public function testEmptyPropertyName()
    {
        $schema = new Schema();
        $schema->additionalProperties = Schema::integer();

        if (PHP_VERSION_ID < 71000) {
            $this->setExpectedException(get_class(new InvalidValue()), 'Empty property name');
        }

        $data = (object)array('' => 1, 'a' => 2, 1 => 3);
        $schema->import($data);
        $schema->export($data);
    }

}