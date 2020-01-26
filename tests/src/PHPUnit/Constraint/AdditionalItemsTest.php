<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Constraint;


use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\Schema;

class AdditionalItemsTest extends \PHPUnit_Framework_TestCase
{
    public function testAdditionalItemsAreNotAllowed()
    {
        $schema = Schema::import(
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
        $schema->in(array(1,2,3,4));
    }

    public function testEmptyPropertyName()
    {
        $schema = new Schema();
        $schema->additionalProperties = Schema::integer();

        if (PHP_VERSION_ID < 70100) {
            $this->setExpectedException(get_class(new InvalidValue()), 'Empty property name');
        }

        $data = (object)array('' => 1, 'a' => 2, 1 => 3);
        $schema->in($data);
        $schema->out($data);
    }

}