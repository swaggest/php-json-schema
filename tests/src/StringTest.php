<?php

namespace Yaoi\Schema\Tests;


use Yaoi\Schema\Exception;
use Yaoi\Schema\Types\StringType;

class StringTest extends \PHPUnit_Framework_TestCase
{
    public function testStringSchema()
    {
        $schema = StringType::makeSchema();
        $schema->import('123');
    }

    public function testStringSchemaException()
    {
        $schema = StringType::makeSchema();
        $this->setExpectedException(get_class(new Exception), 'String required');
        $schema->import(123);
    }

}