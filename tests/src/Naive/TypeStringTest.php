<?php

namespace Yaoi\Schema\Tests\Naive;


use Yaoi\Schema\Exception;
use Yaoi\Schema\Schema;

class TypeStringTest extends \PHPUnit_Framework_TestCase
{
    public function testValid()
    {
        $schema = new Schema(array('type' => 'string'));
        $this->assertSame('123', $schema->import('123'));
    }

    public function testInvalidInteger()
    {
        $this->setExpectedException(get_class(new Exception), 'Wrong type', Exception::INVALID_VALUE);
        $schema = new Schema(array('type' => 'string'));
        $this->assertSame(123, $schema->import(123));
    }
}