<?php


namespace Swaggest\JsonSchema\Tests\PHPUnit\Suite;


use Swaggest\JsonSchema\InvalidRef;
use Swaggest\JsonSchema\Schema;

class SwaggerTest extends \PHPUnit_Framework_TestCase
{
    public function testValidate()
    {
        $schema = Schema::import(json_decode(file_get_contents(__DIR__ . '/../../../resources/swagger-schema.json')));
        $instance = $schema->in(json_decode(file_get_contents(__DIR__ . '/../../../resources/petstore-simple.json')));
    }

    public function testInvalid()
    {
        $schema = Schema::import(json_decode(file_get_contents(__DIR__ . '/../../../resources/swagger-schema.json')));

        $petstore = file_get_contents(__DIR__ . '/../../../resources/petstore-simple.json');

        // Breaking reference to have validation failure.
        $petstore = str_replace('#/definitions/Pet', '#/definitions/Foo', $petstore);

        $failed = false;
        try {
            $instance = $schema->in(json_decode($petstore));
        } catch (InvalidRef $exception) {
            $failed = true;
            $this->assertEquals('Could not resolve #/definitions/Foo@: Foo',
                $exception->getMessage());
        }

        $this->assertTrue($failed);
    }

    public function testInvalid2()
    {
        $schema = Schema::import(json_decode(file_get_contents(__DIR__ . '/../../../resources/swagger-schema.json')));

        $json = <<<'JSON'
{
    "swagger": "2.0",
    "info": {
        "title": "test",
        "version": "1.0.0"
    },
    "paths": {
        "/test": {
            "get": {
                "summary": "test",
                "responses": {
                    "200": {
                        "description": "successful response",
                        "schema": {
                            "$ref": "#/definitions/response"
                        }
                    }
                }
            }
        }
    },
    "definitions": {
        "response": {
            "properties": {
                "foo": {
                    "type": "array",
                    "items": {
                        "$ref": "#/definitions/good"
                    }
                },
                "bar": {
                    "type": "array",
                    "items": {
                        "$ref": "#/definitions/missing1"
                    }
                }
            }
        },
        "good": {
            "properties": {
                "foo": {
                    "$ref": "#/definitions/missing2"
                }
            }
        }
    }
}

JSON;


        $failed = false;
        try {
            $instance = $schema->in(json_decode($json));
        } catch (InvalidRef $exception) {
            $failed = true;
            $this->assertEquals('Could not resolve #/definitions/missing2@: missing2',
                $exception->getMessage());
        }

        $this->assertTrue($failed);
    }

}