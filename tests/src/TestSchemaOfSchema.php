<?php

namespace Yaoi\Schema\Tests;


use Yaoi\Schema\Schema;

class TestSchemaOfSchema extends \PHPUnit_Framework_TestCase
{

    public function testBasic()
    {
        $schemaData = /** @lang JSON */
            <<<'JSON'
           {
    "id": "http://json-schema.org/draft-04/schema#",
    "$schema": "http://json-schema.org/draft-04/schema#",
    "description": "Core schema meta-schema",
    "type": "object",
    "properties": {
        "id": {
            "type": "string",
            "format": "uri"
        },
        "$schema": {
            "type": "string",
            "format": "uri"
        },
        "title": {
            "type": "string"
        },
        "description": {
            "type": "string"
        },
        "properties": {
            "type": "object",
            "additionalProperties": { "$ref": "#" },
            "default": {}
        },
        "type": {
            "anyOf": [
                { "$ref": "#/definitions/simpleTypes" },
                {
                    "type": "array",
                    "items": { "$ref": "#/definitions/simpleTypes" },
                    "minItems": 1,
                    "uniqueItems": true
                }
            ]
        }

    }
}
JSON;
        $schemaData = json_decode($schemaData, 1);
        $schema = new Schema($schemaData);

        $schema->import($schemaData);


    }

}