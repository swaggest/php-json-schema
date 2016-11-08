<?php

namespace Yaoi\Schema\Tests\Naive;


use Yaoi\Schema\Exception;
use Yaoi\Schema\OldSchema;

class TypeStringTest extends \PHPUnit_Framework_TestCase
{
    public function testValid()
    {
        $schema = new OldSchema(array('type' => 'string'));
        $this->assertSame('123', $schema->import('123'));
    }

    public function testInvalidInteger()
    {
        $this->setExpectedException(get_class(new Exception), 'Wrong type', Exception::INVALID_VALUE);
        $schema = new OldSchema(array('type' => 'string'));
        $this->assertSame(123, $schema->import(123));
    }
}