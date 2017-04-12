<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit;


use Swaggest\JsonSchema\Exception\LogicException;
use Swaggest\JsonSchema\Exception\ObjectException;
use Swaggest\JsonSchema\Exception\TypeException;
use Swaggest\JsonSchema\JsonSchema;
use Swaggest\JsonSchema\ProcessingOptions;
use Swaggest\JsonSchema\RefResolver;
use Swaggest\JsonSchema\RemoteRef\Preloaded;

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
}