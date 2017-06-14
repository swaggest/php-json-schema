<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\DumpSchema;


use Swaggest\JsonSchema\JsonSchema;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\SchemaLoader;

class DumpTest extends \PHPUnit_Framework_TestCase
{
    public function testDump()
    {
        $anotherSchema = JsonSchema::object()
            ->setProperty('hello', JsonSchema::boolean())
            ->setProperty('world', JsonSchema::string());


        $schema = JsonSchema::object()
            ->setProperty('sampleInt', JsonSchema::integer())
            ->setProperty('sampleBool', JsonSchema::boolean())
            ->setProperty('sampleString', JsonSchema::string())
            ->setProperty('sampleNumber', JsonSchema::number());
        $schema
            ->setProperty('sampleSelf', $schema)
            ->setProperty('another', $anotherSchema);

        $schemaData = JsonSchema::exportFromSchema($schema);
        $expected = <<<'JSON'
{
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
            "properties": {
                "hello": {
                    "type": "boolean"
                },
                "world": {
                    "type": "string"
                }
            },
            "type": "object"
        }
    },
    "type": "object"
}
JSON;

        $this->assertSame($expected, json_encode($schemaData, JSON_PRETTY_PRINT));
    }

}