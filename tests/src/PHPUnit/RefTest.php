<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit;


use Swaggest\JsonSchema\Exception\LogicException;
use Swaggest\JsonSchema\Exception\ObjectException;
use Swaggest\JsonSchema\Exception\TypeException;
use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\JsonSchema;
use Swaggest\JsonSchema\ProcessingOptions;
use Swaggest\JsonSchema\RefResolver;
use Swaggest\JsonSchema\RemoteRef\Preloaded;
use Swaggest\JsonSchema\Tests\PHPUnit\Spec\SpecTest;

class RefTest extends \PHPUnit_Framework_TestCase
{

    public function testResolve()
    {
        $schemaData = array(
            '$ref' => '#/definitions/test',
            'definitions' => array(
                'test' => array(
                    'type' => 'object',
                    'properties' => array(
                        'one' => array(
                            'type' => 'integer'
                        ),
                    ),
                ),
            ),
        );

        $schema = JsonSchema::importToSchema(json_decode(json_encode($schemaData)));

        $import = $schema->import((object)array('one' => 123));
        $this->assertSame(123, $import->one);
    }

    public function testRootResolve()
    {
        $schemaData = array(
            'type' => 'object',
            'properties' => array(
                'one' => array(
                    '$ref' => '#/definitions/test',
                ),
                'two' => array(
                    '$ref' => '#/definitions/test',
                ),
            ),
            'definitions' => array(
                'test' => array(
                    'type' => 'object',
                    'properties' => array(
                        'one' => array(
                            'type' => 'integer'
                        ),
                    ),
                ),
            ),
        );


        $schema = JsonSchema::importToSchema(json_decode(json_encode($schemaData)));

        $object = $schema->import((object)array(
            'two' => (object)array(
                'one' => 123
            )
        )
        );
        $this->assertSame(123, $object->two->one);

    }

    public function testDefinitions()
    {
        $schemaJson = '{"$ref": "http://json-schema.org/draft-04/schema#"}';
        $dataJson = '{
                    "definitions": {
                        "foo": {"type": "integer"}
                    }
                }';

        $options = new ProcessingOptions();
        $options->setRemoteRefProvider(new Preloaded());
        $schema = JsonSchema::importToSchema(json_decode($schemaJson), $options);

        $schema->import(json_decode($dataJson));
    }


    public function testInvalidDefinition()
    {
        $schemaJson = '{"$ref": "http://json-schema.org/draft-04/schema#"}';
        $dataJson = '{
                    "definitions": {
                        "foo": {"type": 1}
                    }
                }';

        $options = new ProcessingOptions();
        $options->setRemoteRefProvider(new Preloaded());
        $schema = JsonSchema::importToSchema(json_decode($schemaJson), $options);

        $this->setExpectedException(get_class(new LogicException()));
        $result = $schema->import(json_decode($dataJson));
    }

    public function testNestedRefs()
    {
        $schemaJson = '{
            "definitions": {
                "a": {"type": "integer"},
                "b": {"$ref": "#/definitions/a"},
                "c": {"$ref": "#/definitions/b"}
            },
            "$ref": "#/definitions/c"
        }';
        $dataJson = 'a';

        $options = new ProcessingOptions();
        $options->setRemoteRefProvider(new Preloaded());
        $schema = JsonSchema::importToSchema(json_decode($schemaJson), $options);

        $this->setExpectedException(get_class(new TypeException()));
        $schema->import(json_decode($dataJson));
    }

    public function testRemoteRef()
    {
        $refProvider = new Preloaded();
        $refProvider->setSchemaData(
            'http://localhost:1234/subSchemas.json',
            json_decode(file_get_contents(
                __DIR__ . '/../../../spec/JSON-Schema-Test-Suite/remotes/subSchemas.json'
            ))
        );

        $options = new ProcessingOptions();
        $options->setRemoteRefProvider($refProvider);
        $schemaJson = <<<'JSON'
{"$ref": "http://localhost:1234/subSchemas.json#/integer"}
JSON;
        $schema = JsonSchema::importToSchema(json_decode($schemaJson), $options);

        $schema->import(1);
        $this->setExpectedException(get_class(new TypeException()));
        $schema->import('a');
    }

    public function testSimple()
    {
        $refResolver = new RefResolver('{"$ref": "http://json-schema.org/draft-04/schema#"}');
        $refResolver->setRemoteRefProvider(new Preloaded());
        $refResolver->setResolutionScope('http://json-schema.org/draft-04/schema#');
        $ref = $refResolver->resolveReference('#/definitions/positiveInteger');
        $this->assertSame('integer', $ref->getData()->type);
        $this->assertSame(0, $ref->getData()->minimum);
    }

    public function testRootRef()
    {
        $schemaJson = <<<'JSON'
{
    "properties": {
        "foo": {"$ref": "#"}
    },
    "additionalProperties": false
}
JSON;
        $dataJson = <<<'JSON'
{"foo": {"foo": false}}
JSON;

        $dataInvalidJson = <<<'JSON'
{"foo": {"bar": false}}
JSON;

        $schema = JsonSchema::importToSchema(json_decode($schemaJson));
        $schema->import(json_decode($dataJson));
        $this->setExpectedException(get_class(new ObjectException()));
        $schema->import(json_decode($dataInvalidJson));
    }


    public function testInvalidTree()
    {
        $schemaJson = <<<'JSON'
{
    "id": "http:\/\/localhost:1234\/tree",
    "description": "tree of nodes",
    "type": "object",
    "properties": {
        "meta": {
            "type": "string"
        },
        "nodes": {
            "type": "array",
            "items": {
                "$ref": "node"
            }
        }
    },
    "required": [
        "meta",
        "nodes"
    ],
    "definitions": {
        "node": {
            "id": "http:\/\/localhost:1234\/node",
            "description": "node",
            "type": "object",
            "properties": {
                "value": {
                    "type": "number"
                },
                "subtree": {
                    "$ref": "tree"
                }
            },
            "required": [
                "value"
            ]
        }
    }
}
JSON;

        $dataJson = <<<'JSON'
{
    "meta": "root",
    "nodes": [
        {
            "value": 1,
            "subtree": {
                "meta": "child",
                "nodes": [
                    {
                        "value": "string is invalid"
                    },
                    {
                        "value": 1.2
                    }
                ]
            }
        },
        {
            "value": 2,
            "subtree": {
                "meta": "child",
                "nodes": [
                    {
                        "value": 2.1
                    },
                    {
                        "value": 2.2
                    }
                ]
            }
        }
    ]
}

JSON;

        $schema = JsonSchema::importToSchema(json_decode($schemaJson));

        $this->setExpectedException(get_class(new InvalidValue()));
        $schema->import(json_decode($dataJson));
    }

    public function testScopeChange()
    {
        $testData = json_decode(<<<'JSON'
{
    "description": "base URI change - change folder",
    "schema": {
        "id": "http://localhost:1234/scope_change_defs1.json",
        "type" : "object",
        "properties": {
            "list": {"$ref": "#/definitions/baz"}
        },
        "definitions": {
            "baz": {
                "id": "folder/",
                "type": "array",
                "items": {"$ref": "folderInteger.json"}
            }
        }
    },
    "tests": [
        {
            "description": "number is valid",
            "data": {"list": [1]},
            "valid": true
        },
        {
            "description": "string is invalid",
            "data": {"list": ["a"]},
            "valid": false
        }
    ]
}
JSON
        );

        $options = new ProcessingOptions();
        $options->remoteRefProvider = SpecTest::getProvider();
        $schema = JsonSchema::importToSchema($testData->schema, $options);

        $schema->import($testData->tests[0]->data);
        $this->setExpectedException(get_class(new InvalidValue()));
        $schema->import($testData->tests[1]->data);
    }


    public function testScopeChangeSubschema()
    {
        $testData = json_decode(<<<'JSON'
{
    "description": "base URI change - change folder in subschema",
    "schema": {
        "id": "http://localhost:1234/scope_change_defs2.json",
        "type" : "object",
        "properties": {
            "list": {"$ref": "#/definitions/baz/definitions/bar"}
        },
        "definitions": {
            "baz": {
                "id": "folder/",
                "definitions": {
                    "bar": {
                        "type": "array",
                        "items": {"$ref": "folderInteger.json"}
                    }
                }
            }
        }
    },
    "tests": [
        {
            "description": "number is valid",
            "data": {"list": [1]},
            "valid": true
        },
        {
            "description": "string is invalid",
            "data": {"list": ["a"]},
            "valid": false
        }
    ]
}
JSON
);
        $options = new ProcessingOptions();
        $options->remoteRefProvider = SpecTest::getProvider();
        $schema = JsonSchema::importToSchema($testData->schema, $options);

        $schema->import($testData->tests[0]->data);
        $this->setExpectedException(get_class(new InvalidValue()));
        $schema->import($testData->tests[1]->data);


    }


    public function testExtRef()
    {
        $testData = json_decode(<<<'JSON'
    {
        "description": "external ref within remote ref",
        "schema": {
            "$ref": "http://localhost:1234/subSchemas.json#/refToExternalInteger"
        },
        "tests": [
            {
                "description": "external ref within ref valid",
                "data": 1,
                "valid": true
            },
            {
                "description": "external ref within ref invalid",
                "data": "a",
                "valid": false
            }
        ]
    }
JSON
        );

        $schema = JsonSchema::importToSchema($testData->schema, new ProcessingOptions(
                SpecTest::getProvider())
        );
        $schema->import($testData->tests[0]->data);
        $this->setExpectedException(get_class(new InvalidValue()));
        $schema->import($testData->tests[1]->data);

    }


    public function testRefWithinRef()
    {
        $testData = json_decode(<<<'JSON'
    {
        "description": "ref within remote ref",
        "schema": {
            "$ref": "http://localhost:1234/subSchemas.json#/refToInteger"
        },
        "tests": [
            {
                "description": "ref within ref valid",
                "data": 1,
                "valid": true
            },
            {
                "description": "ref within ref invalid",
                "data": "a",
                "valid": false
            }
        ]
    }
JSON
);
        $schema = JsonSchema::importToSchema($testData->schema, new ProcessingOptions(
                SpecTest::getProvider())
        );
        $schema->import($testData->tests[0]->data);
        $this->setExpectedException(get_class(new InvalidValue()));
        $schema->import($testData->tests[1]->data);

    }

}