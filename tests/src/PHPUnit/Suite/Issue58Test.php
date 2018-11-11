<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Suite;

use Swaggest\JsonSchema\Constraint\Format;
use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Tests\Helper\SimpleClass;

class Issue58Test extends \PHPUnit_Framework_TestCase
{
    private function getData()
    {
        $data = new SimpleClass();
        $data->id = 1234;
        $data->username = "John";
        $data->email = "john@doe.com";
        return $data;
    }

    private function getSchema()
    {
        $schema = Schema::object();
        $schema
            ->setProperty('id', Schema::integer())
            ->setProperty('username', Schema::string())
            ->setProperty('email', Schema::string()->setFormat(Format::EMAIL));

        // checking leak of private/protected properties
        $schema->additionalProperties = false;
        return $schema;
    }

    public function testSimpleClass()
    {
        $this->getSchema()->out($this->getData());
    }

    public function testSimpleClassFailed()
    {
        $data = $this->getData();
        $data->email = 'bla-bla';
        $this->setExpectedException(get_class(new InvalidValue()));
        $this->getSchema()->out($data);
    }

}