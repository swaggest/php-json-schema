<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Constraint;


use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\SchemaLoader;

class AdditionalItemsTest extends \PHPUnit_Framework_TestCase
{
    public function testAdditionalItemsAreNotAllowed()
    {
        $schema = SchemaLoader::create()->readSchema(
            array(
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

}