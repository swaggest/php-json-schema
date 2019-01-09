<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Suite;

use Swaggest\JsonSchema\Schema;

class Issue66Test extends \PHPUnit_Framework_TestCase
{
    public function testIssue()
    {
        $schemaData = json_decode(<<<'JSON'
{
    "$schema": "http://json-schema.org/draft-07/schema#",
    "$id": "urn:example:api:response-example",
    "type": "object",
    "additionalProperties": false,
    "required": ["confirmed", "to_pay"],
    "definitions": {
        "example_item": {
            "type": "object",
            "additionalProperties": false,
            "required": [
                "_type",
                "count"
            ],
            "properties": {
                "_type": {
                    "$id": "#/definitions/example_item/properties/confirmed/properties/_type",
                    "type": "string",
                    "enum": ["example_item"]
                },
                "count": {
                    "$id": "#/definitions/example_item/properties/confirmed/properties/count",
                    "type": "integer"
                }
            }
        }
    },
    "properties": {
        "confirmed": {
            "$ref": "#/definitions/example_item"
        },
        "to_pay": {
            "$ref": "#/definitions/example_item"
        }
    }
}
JSON
);
        $schema = Schema::import($schemaData);
        $res = $schema->in(json_decode('{"confirmed":{"count":123, "_type": "example_item"}, "to_pay":{"count":123, "_type": "example_item"}}'));
        $this->assertSame(123, $res->confirmed->count);
    }

}