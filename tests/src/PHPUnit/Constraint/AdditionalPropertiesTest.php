<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Constraint;


use Swaggest\JsonSchema\Schema;

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
        $schema = Schema::import($schemaData);
        $schema->in(json_decode('{"foo": 1}'));
    }

}