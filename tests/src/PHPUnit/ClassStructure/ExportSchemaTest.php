<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\ClassStructure;


use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Tests\Helper\DbId;

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

}