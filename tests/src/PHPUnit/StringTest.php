<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit;


use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\Schema;

class StringTest extends \PHPUnit_Framework_TestCase
{
    public function testStringSchema()
    {
        $schema = Schema::string();
        $schema->import('123');
    }

    public function testStringSchemaException()
    {
        $schema = Schema::string();
        $this->setExpectedException(get_class(new InvalidValue), 'String expected, 123 received');
        $schema->import(123);
    }

}