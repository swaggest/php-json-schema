<?php

namespace Yaoi\Schema\Tests;


use Yaoi\Schema\Exception;
use Yaoi\Schema\Types\StringType;

class StringTest extends \PHPUnit_Framework_TestCase
{
    public function testString()
    {
        $schema = new StringType();

        $this->assertFalse($schema->isValid(123));
        $this->assertTrue($schema->isValid('aaa'));
    }


    public function testStringSchema()
    {
        $schema = StringType::makeSchema();
        $schema->import('123');
    }

    public function testStringSchemaException()
    {
        $schema = StringType::makeSchema();
        $this->setExpectedException(get_class(new Exception), 'Validation failed');
        $schema->import(123);
    }

}