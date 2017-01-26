<?php

namespace Yaoi\Schema\Tests\PHPUnit;


use Yaoi\Schema\InvalidValue;
use Yaoi\Schema\Schema;
use Yaoi\Schema\Types\StringType;

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
        $this->setExpectedException(get_class(new InvalidValue), 'String required');
        $schema->import(123);
    }

}