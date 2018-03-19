<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Ref;


use Swaggest\JsonSchema\Context;
use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\RemoteRef\Preloaded;
use Swaggest\JsonSchema\Schema;

class FileResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testFileResolver()
    {
        $refProvider = new Preloaded();
        $refProvider->setSchemaData(
            'file://baseTypes.json',
            json_decode(<<<'JSON'
{
  "stringFromOutside": {
    "type": "string"
  }
}
JSON
)
        );

        $schemaData = json_decode(<<<'JSON'
{
    "$schema": "http://json-schema.org/schema#",
    "type": "object",
    "properties": {
        "sample": { "$ref": "file://baseTypes.json#/stringFromOutside" }
    },
    "required": ["sample"],
    "additionalProperties": false
}
JSON
);
        $options = new Context();
        $options->remoteRefProvider = $refProvider;
        $schema = Schema::import($schemaData, $options);

        $schema->in(json_decode('{"sample": "some-string"}')); // no exception for string
        try {
            $schema->in(json_decode('{"sample": 1}')); // exception for int
            $this->fail('Exception expected');
        } catch (InvalidValue $exception) {
            $expected = <<<'TEXT'
Swaggest\JsonSchema\Exception\Error Object
(
    [error] => String expected, 1 received
    [schemaPointers] => Array
        (
            [0] => /properties/sample/$ref
            [1] => file://baseTypes.json#/stringFromOutside
        )

    [dataPointer] => /sample
    [processingPath] => #->properties:sample->$ref[file~2//baseTypes.json#/stringFromOutside]
    [subErrors] => 
)

TEXT;

            $this->assertSame($expected, print_r($exception->inspect(), 1));
        }
    }

}