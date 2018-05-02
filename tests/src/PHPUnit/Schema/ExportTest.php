<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Schema;


use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ObjectItem;
use Swaggest\JsonSchema\Tests\Helper\RefClass;

class ExportTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @throws \Swaggest\JsonSchema\Exception
     * @throws \Swaggest\JsonSchema\InvalidValue
     */
    public function testRecursiveExport()
    {
        $schemaData = json_decode(<<<'JSON'
{
    "type":"object",
    "properties":{
        "scalar":{"type":"integer"},
        "nestedObject":{"$ref":"#/definitions/nested"},
        "referencedValue":{"$ref":"#/definitions/reference"},
        "veryNestedObject":{
            "type":"object",
            "properties": {
                "nesteder":{"$ref":"#/definitions/nested"}
            }
        },
        "arrayOfObjects":{
            "type":"array",
            "items":{"$ref":"#/definitions/nested"}
        }
    },
    "definitions":{
        "nested":{
            "type":"object",
            "properties":{
                "anotherScalar":{"type":"string"}
            }
        },
        "reference":{
            "properties":{
                "$ref":{"type":"string","format":"uri-reference"}
            }
        }
    }
}
JSON
        );
        $schema = Schema::import($schemaData);
        $imported = $schema->in(json_decode(<<<'JSON'
{
    "scalar":123,
    "nestedObject":{"anotherScalar":"abc"},
    "referencedValue":{"$ref":"#/veryNestedObject/nesteder"},
    "veryNestedObject":{"nesteder":{"anotherScalar":"hij"}},
    "arrayOfObjects": [{"anotherScalar":"abc"}, {"anotherScalar":"def"}]
}
JSON
        ));
        $this->assertTrue($imported instanceof ObjectItem);
        $this->assertTrue($imported->nestedObject instanceof ObjectItem);
        $this->assertTrue($imported->arrayOfObjects[0] instanceof ObjectItem);
        $this->assertTrue($imported->arrayOfObjects[1] instanceof ObjectItem);
        $this->assertTrue($imported->veryNestedObject->nesteder instanceof ObjectItem);
        $this->assertTrue($imported->referencedValue instanceof ObjectItem);
        $this->assertSame($imported->veryNestedObject->nesteder->anotherScalar, $imported->referencedValue->anotherScalar);
        $this->assertNotSame($imported->veryNestedObject->nesteder, $imported->referencedValue); // referenced object is a different instance with same data

        $exported = $schema->out($imported);
        $this->assertTrue($exported instanceof \stdClass);
        $this->assertTrue($exported->nestedObject instanceof \stdClass);
        $this->assertTrue($exported->arrayOfObjects[0] instanceof \stdClass);
        $this->assertTrue($exported->arrayOfObjects[1] instanceof \stdClass);
        $this->assertTrue($exported->veryNestedObject->nesteder instanceof \stdClass,
            "Very nested object should be \stdClass");
        $this->assertTrue($exported->referencedValue instanceof \stdClass);
        $this->assertSame('#/veryNestedObject/nesteder', $exported->referencedValue->{Schema::PROP_REF});
    }

    /**
     * @throws \Swaggest\JsonSchema\Exception
     * @throws \Swaggest\JsonSchema\InvalidValue
     */
    public function testVeryNestedObject()
    {
        $schemaData = json_decode(<<<'JSON'
{
    "properties":{
        "veryNestedObject":{
            "properties": {
                "nesteder":{"$ref":"#/definitions/nested"}
            }
        }
    },
    "definitions":{
        "nested":{
            "properties":{
                "anotherScalar":{"type":"string"}
            }
        }
    }
}
JSON
        );
        $schema = Schema::import($schemaData);
        $imported = $schema->in(json_decode(<<<'JSON'
{
    "veryNestedObject":{"nesteder":{"anotherScalar":"hij"}}
}
JSON
        ));
        $this->assertTrue($imported->veryNestedObject->nesteder instanceof ObjectItem);

        $exported = $schema->out($imported);
        $this->assertTrue($exported->veryNestedObject->nesteder instanceof \stdClass);
    }

    /**
     * @throws \Swaggest\JsonSchema\Exception
     * @throws \Swaggest\JsonSchema\InvalidValue
     */
    public function testVeryNestedObjectWithReference()
    {
        $schemaData = json_decode(<<<'JSON'
{
    "properties":{
        "referencedValue":{"$ref":"#/definitions/reference"},
        "veryNestedObject":{
            "properties": {
                "nesteder":{"$ref":"#/definitions/nested"}
            }
        }
    },
    "definitions":{
        "nested":{
            "properties":{
                "anotherScalar":{"type":"string"}
            }
        },
        "reference":{
            "properties":{
                "$ref":{"type":"string","format":"uri-reference"}
            }
        }
    }
}
JSON
        );
        $schema = Schema::import($schemaData);
        $imported = $schema->in(json_decode(<<<'JSON'
{
    "referencedValue":{"$ref":"#/veryNestedObject/nesteder"},
    "veryNestedObject":{"nesteder":{"anotherScalar":"hij"}}
}
JSON
        ));
        $this->assertTrue($imported->veryNestedObject->nesteder instanceof ObjectItem);

        $exported = $schema->out($imported);
        $this->assertTrue($exported->veryNestedObject->nesteder instanceof \stdClass);
    }

    public function testRefClass()
    {
        $schema = RefClass::schema()->exportSchema();
        $this->assertSame('{"properties":{"$ref":{"type":"string","format":"uri-reference"}}}', json_encode($schema));
        $schemaData = Schema::export($schema);
        $this->assertSame(
            '{"properties":{"$ref":{"type":"string","format":"uri-reference"}}}',
            json_encode($schemaData, JSON_UNESCAPED_SLASHES)
        );
    }

}