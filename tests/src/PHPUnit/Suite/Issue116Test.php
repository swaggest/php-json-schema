<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Suite;

use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\Schema;

class Issue116Test extends \PHPUnit_Framework_TestCase
{
    public function testIssue()
    {
        $schemaData = json_decode('{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "title": "Parent",
  "type": "object",
  "required": [
    "title",
    "child"
  ],
  "properties": {
    "title": {
      "type": "string"
    },
    "second_prop": {
      "type": "uri"
    },
    "child": {
      "type": "object",
      "required": [
        "title"
      ],
      "properties": {
        "title": {
          "type": "string"
        }
      }
    }
  }
}');

        $exceptionCaught = false;
        try {
            Schema::import($schemaData);
        } catch (InvalidValue $e) {
            $exceptionCaught = true;

            $this->assertEquals('No valid results for anyOf {
  0: Enum failed, enum: ["array","boolean","integer","null","number","object","string"] at #->properties:properties->additionalProperties:second_prop->properties:type->anyOf[0]
  1: Array expected, "uri" received at #->properties:properties->additionalProperties:second_prop->properties:type->anyOf[1]
} at #->properties:properties->additionalProperties:second_prop->properties:type', $e->getMessage());
        }

        $this->assertTrue($exceptionCaught);
    }


    public function testIssueFile()
    {
        $path = __DIR__ . '/schema.json';
        $path = substr($path, strlen(getcwd()) + 1);

        $exceptionCaught = false;
        try {
            Schema::import($path);
        } catch (InvalidValue $e) {
            $exceptionCaught = true;

            $this->assertEquals('No valid results for anyOf {
  0: Enum failed, enum: ["array","boolean","integer","null","number","object","string"] at #->$ref:tests/src/PHPUnit/Suite/schema.json->properties:properties->additionalProperties:second_prop->properties:type->anyOf[0]
  1: Array expected, "uri" received at #->$ref:tests/src/PHPUnit/Suite/schema.json->properties:properties->additionalProperties:second_prop->properties:type->anyOf[1]
} at #->$ref:tests/src/PHPUnit/Suite/schema.json->properties:properties->additionalProperties:second_prop->properties:type', $e->getMessage());
        }

        $this->assertTrue($exceptionCaught);

    }
}