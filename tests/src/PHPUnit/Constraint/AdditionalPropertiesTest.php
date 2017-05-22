<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Constraint;


use Swaggest\JsonSchema\JsonSchema;

class AdditionalPropertiesTest extends \PHPUnit_Framework_TestCase
{
    public function testOne()
    {
        $json = <<<JSON
{
    "properties": {"foo": {}, "bar": {}},
    "patternProperties": { "^v": {} },
    "additionalProperties": false
}
JSON;

        $schemaData = json_decode($json);
        $schema = JsonSchema::importToSchema($schemaData);
        $schema->import(json_decode('{"foo": 1}'));
    }

}