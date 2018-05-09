<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Ref;


use Swaggest\JsonSchema\Context;
use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\Schema;

class RecursiveReferencesTest extends \PHPUnit_Framework_TestCase
{
    public function testRecursiveReferences()
    {
        $schemaJson = <<<'JSON'
{
    "allOf": [
        {"$ref": "#/a"}
    ],
    "a": {"$ref": "#/b"},
    "b": {"$ref": "#/c"},
    "c": {"$ref": "#/d"},
    "d": {"type": "string"}
}
JSON;
        $options = new Context();
        $schema = Schema::import(json_decode($schemaJson), $options);


        $schema->in("seven"); // lucky number

        $this->setExpectedException(get_class(new InvalidValue()),
            'String expected, 13 received at #->allOf[0]->$ref[#/a]->$ref[#/b]->$ref[#/c]->$ref[#/d]');
        $schema->in(13); // unlucky number

    }

}