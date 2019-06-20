<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Error;

use Swaggest\JsonDiff\JsonPointer;
use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\Schema;

class ErrorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @throws \Exception
     * @throws \Swaggest\JsonSchema\Exception
     * @throws \Swaggest\JsonSchema\InvalidValue
     */
    public function testErrorMessage()
    {
        $schemaData = json_decode(<<<'JSON'
{
    "$schema": "http://json-schema.org/schema#",
    "type": "object",
    "properties": {
        "root": {
            "type": "object",
            "patternProperties": {
                "^[a-zA-Z0-9_]+$": {
                    "oneOf": [
                        {"enum": ["a"]},
                        {"enum": ["b"]},
                        {"$ref": "#/ref-to-cde"}
                    ]
                }
            }
        }
    },
    "ref-to-cde": {"$ref":"#/cde"},
    "cde": {
        "anyOf": [
            {"enum":["c"]}, 
            {"enum":["d"]}, 
            {"enum":["e"]} 
        ]
    }
}
JSON
        );
        $schema = Schema::import($schemaData);

        $expectedException = <<<'TEXT'
No valid results for oneOf {
 0: Enum failed, enum: ["a"], data: "f" at #->properties:root->patternProperties[^[a-zA-Z0-9_]+$]:zoo->oneOf[0]
 1: Enum failed, enum: ["b"], data: "f" at #->properties:root->patternProperties[^[a-zA-Z0-9_]+$]:zoo->oneOf[1]
 2: No valid results for anyOf {
   0: Enum failed, enum: ["c"], data: "f" at #->properties:root->patternProperties[^[a-zA-Z0-9_]+$]:zoo->oneOf[2]->$ref[#/ref-to-cde]->$ref[#/cde]->anyOf[0]
   1: Enum failed, enum: ["d"], data: "f" at #->properties:root->patternProperties[^[a-zA-Z0-9_]+$]:zoo->oneOf[2]->$ref[#/ref-to-cde]->$ref[#/cde]->anyOf[1]
   2: Enum failed, enum: ["e"], data: "f" at #->properties:root->patternProperties[^[a-zA-Z0-9_]+$]:zoo->oneOf[2]->$ref[#/ref-to-cde]->$ref[#/cde]->anyOf[2]
 } at #->properties:root->patternProperties[^[a-zA-Z0-9_]+$]:zoo->oneOf[2]->$ref[#/ref-to-cde]->$ref[#/cde]
} at #->properties:root->patternProperties[^[a-zA-Z0-9_]+$]:zoo
TEXT;

        $errorInspected = <<<'TEXT'
Swaggest\JsonSchema\Exception\Error Object
(
    [error] => No valid results for oneOf
    [schemaPointers] => Array
        (
            [0] => /properties/root/patternProperties/^[a-zA-Z0-9_]+$
        )

    [dataPointer] => /root/zoo
    [processingPath] => #->properties:root->patternProperties[^[a-zA-Z0-9_]+$]:zoo
    [subErrors] => Array
        (
            [0] => Swaggest\JsonSchema\Exception\Error Object
                (
                    [error] => Enum failed, enum: ["a"], data: "f"
                    [schemaPointers] => Array
                        (
                            [0] => /properties/root/patternProperties/^[a-zA-Z0-9_]+$/oneOf/0
                        )

                    [dataPointer] => /root/zoo
                    [processingPath] => #->properties:root->patternProperties[^[a-zA-Z0-9_]+$]:zoo->oneOf[0]
                    [subErrors] => 
                )

            [1] => Swaggest\JsonSchema\Exception\Error Object
                (
                    [error] => Enum failed, enum: ["b"], data: "f"
                    [schemaPointers] => Array
                        (
                            [0] => /properties/root/patternProperties/^[a-zA-Z0-9_]+$/oneOf/1
                        )

                    [dataPointer] => /root/zoo
                    [processingPath] => #->properties:root->patternProperties[^[a-zA-Z0-9_]+$]:zoo->oneOf[1]
                    [subErrors] => 
                )

            [2] => Swaggest\JsonSchema\Exception\Error Object
                (
                    [error] => No valid results for anyOf
                    [schemaPointers] => Array
                        (
                            [0] => /properties/root/patternProperties/^[a-zA-Z0-9_]+$/oneOf/2/$ref
                            [1] => /ref-to-cde/$ref
                            [2] => /cde
                        )

                    [dataPointer] => /root/zoo
                    [processingPath] => #->properties:root->patternProperties[^[a-zA-Z0-9_]+$]:zoo->oneOf[2]->$ref[#/ref-to-cde]->$ref[#/cde]
                    [subErrors] => Array
                        (
                            [0] => Swaggest\JsonSchema\Exception\Error Object
                                (
                                    [error] => Enum failed, enum: ["c"], data: "f"
                                    [schemaPointers] => Array
                                        (
                                            [0] => /properties/root/patternProperties/^[a-zA-Z0-9_]+$/oneOf/2/$ref
                                            [1] => /ref-to-cde/$ref
                                            [2] => /cde/anyOf/0
                                        )

                                    [dataPointer] => /root/zoo
                                    [processingPath] => #->properties:root->patternProperties[^[a-zA-Z0-9_]+$]:zoo->oneOf[2]->$ref[#/ref-to-cde]->$ref[#/cde]->anyOf[0]
                                    [subErrors] => 
                                )

                            [1] => Swaggest\JsonSchema\Exception\Error Object
                                (
                                    [error] => Enum failed, enum: ["d"], data: "f"
                                    [schemaPointers] => Array
                                        (
                                            [0] => /properties/root/patternProperties/^[a-zA-Z0-9_]+$/oneOf/2/$ref
                                            [1] => /ref-to-cde/$ref
                                            [2] => /cde/anyOf/1
                                        )

                                    [dataPointer] => /root/zoo
                                    [processingPath] => #->properties:root->patternProperties[^[a-zA-Z0-9_]+$]:zoo->oneOf[2]->$ref[#/ref-to-cde]->$ref[#/cde]->anyOf[1]
                                    [subErrors] => 
                                )

                            [2] => Swaggest\JsonSchema\Exception\Error Object
                                (
                                    [error] => Enum failed, enum: ["e"], data: "f"
                                    [schemaPointers] => Array
                                        (
                                            [0] => /properties/root/patternProperties/^[a-zA-Z0-9_]+$/oneOf/2/$ref
                                            [1] => /ref-to-cde/$ref
                                            [2] => /cde/anyOf/2
                                        )

                                    [dataPointer] => /root/zoo
                                    [processingPath] => #->properties:root->patternProperties[^[a-zA-Z0-9_]+$]:zoo->oneOf[2]->$ref[#/ref-to-cde]->$ref[#/cde]->anyOf[2]
                                    [subErrors] => 
                                )

                        )

                )

        )

)

TEXT;


        try {
            $schema->in(json_decode('{"root":{"zoo":"f"}}'));
            $this->fail('Exception expected');
        } catch (InvalidValue $exception) {
            $this->assertSame($expectedException, $exception->getMessage());
            $error = $exception->inspect();
            $this->assertSame($errorInspected, print_r($error, 1));
            $this->assertSame('/properties/root/patternProperties/^[a-zA-Z0-9_]+$', $exception->getSchemaPointer());

            // Resolving schema pointer to schema data.
            $failedSchemaData = JsonPointer::getByPointer($schemaData, $exception->getSchemaPointer());
            $this->assertEquals(json_decode(<<<'JSON'
{
    "oneOf": [
        {"enum": ["a"]},
        {"enum": ["b"]},
        {"$ref": "#/ref-to-cde"}
    ]
}
JSON
            ), $failedSchemaData);

            // Getting failed sub schema.
            $failedSchema = $exception->getFailedSubSchema($schema);
            $this->assertEquals($failedSchema->oneOf[0]->enum, ["a"]);
            $this->assertEquals($failedSchema->oneOf[1]->enum, ["b"]);
            $this->assertEquals($failedSchema->oneOf[2]->anyOf[0]->enum, ["c"]);
            $this->assertEquals($failedSchema->oneOf[2]->anyOf[1]->enum, ["d"]);
            $this->assertEquals($failedSchema->oneOf[2]->anyOf[2]->enum, ["e"]);

            $this->assertSame('/root/zoo', $exception->getDataPointer());
        }
    }

    public function testRequiredError()
    {
        $schema = Schema::import(__DIR__ . '/../../../resources/required.json');
        $data = (object)['a' => 1, 'b' => 2, 'c' => 3];

        $schema->in($data);

        unset($data->b);

        try {
            $schema->in($data);
            $this->fail('Exception expected');
        } catch (InvalidValue $e) {
            $failedSchema = $e->getFailedSubSchema($schema);
            $this->assertEquals(["a", "b", "c"], $failedSchema->required);
        }
    }


    public function testNoSubErrors()
    {
        $schema = Schema::import(json_decode(<<<'JSON'
{
    "not": {
        "type": "string"
    }
}
JSON
        ));

        try {
            $schema->in('abc');
        } catch (InvalidValue $exception) {
            $this->assertSame('Not {"type":"string"} expected, "abc" received at #->not', $exception->getMessage());

            $error = $exception->inspect();
            $this->assertSame('Not {"type":"string"} expected, "abc" received', $error->error);
        }
    }

}