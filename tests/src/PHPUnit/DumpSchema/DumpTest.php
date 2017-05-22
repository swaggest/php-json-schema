<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\DumpSchema;


use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\SchemaLoader;

class DumpTest extends \PHPUnit_Framework_TestCase
{
    public function testDump()
    {
        $anotherSchema = Schema::object()
            ->setProperty('hello', Schema::boolean())
            ->setProperty('world', Schema::string());


        $schema = Schema::object()
            ->setProperty('sampleInt', Schema::integer())
            ->setProperty('sampleBool', Schema::boolean())
            ->setProperty('sampleString', Schema::string())
            ->setProperty('sampleNumber', Schema::number());
        $schema
            ->setProperty('sampleSelf', $schema)
            ->setProperty('another', $anotherSchema);

        $loader = SchemaLoader::create();

        $schemaData = $loader->dumpSchema($schema);
        $expected = <<<'JSON'
{
    "type": "object",
    "properties": {
        "sampleInt": {
            "type": "integer"
        },
        "sampleBool": {
            "type": "boolean"
        },
        "sampleString": {
            "type": "string"
        },
        "sampleNumber": {
            "type": "number"
        },
        "sampleSelf": {
            "$ref": "#"
        },
        "another": {
            "type": "object",
            "properties": {
                "hello": {
                    "type": "boolean"
                },
                "world": {
                    "type": "string"
                }
            }
        }
    }
}
JSON;

        $this->assertSame($expected, json_encode($schemaData, JSON_PRETTY_PRINT));
    }

}