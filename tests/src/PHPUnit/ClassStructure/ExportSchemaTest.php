<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\ClassStructure;


use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Tests\Helper\DbId;
use Swaggest\JsonSchema\Tests\Helper\DeepRefRoot;

class ExportSchemaTest extends \PHPUnit_Framework_TestCase
{
    public function testSchemaExport()
    {
        $schema = DbId::schema();
        $schemaData = Schema::export($schema);
        $expected = <<<'JSON'
{
    "properties": {
        "table": {
            "$ref": "#/definitions/Swaggest\\JsonSchema\\Tests\\Helper\\DbTable"
        }
    },
    "definitions": {
        "Swaggest\\JsonSchema\\Tests\\Helper\\DbTable": {
            "properties": {
                "tableName": {
                    "type": "string"
                }
            }
        }
    }
}
JSON;

        $this->assertSame($expected, json_encode($schemaData, JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES));
    }

    public function testDeepRef()
    {
        $schema = DeepRefRoot::schema();
        $schemaData = Schema::export($schema);

        $expected = <<<'JSON'
{
    "properties": {
        "prop": {
            "$ref": "#/definitions/lvlD"
        },
        "directTitle": {
            "$ref": "http://json-schema.org/draft-04/schema#/properties/title"
        },
        "intermediateTitle": {
            "$ref": "#/definitions/title"
        }
    },
    "type": "string",
    "definitions": {
        "lvlA": {
            "type": "object"
        },
        "lvlB": {
            "$ref": "#/definitions/lvlA"
        },
        "lvlC": {
            "$ref": "#/definitions/lvlB"
        },
        "lvlD": {
            "$ref": "#/definitions/lvlC"
        },
        "title": {
            "$ref": "http://json-schema.org/draft-04/schema#/properties/title"
        }
    }
}
JSON;

        $this->assertSame($expected, json_encode($schemaData, JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES));


    }

    public function testDeepRefSchema()
    {
        $schemaJson = <<<'JSON'
{
    "definitions": {
        "lvl1": {
            "$ref": "#/definitions/lvl2"
        },
        "lvl2": {
            "$ref": "#/definitions/lvl3"
        },
        "lvl3": {
            "$ref": "#/definitions/lvl4"
        },
        "lvl4": {
            "type": "integer"
        }
    },
    "properties": {
        "prop": {
            "$ref": "#/definitions/lvl1"
        }
    },
    "type": "object"
}
JSON;
        $schemaData = json_decode($schemaJson);

        $schema = Schema::import($schemaData);
        $exported = Schema::export($schema);
        $this->assertSame($schemaJson, json_encode($exported, JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES));
    }

}