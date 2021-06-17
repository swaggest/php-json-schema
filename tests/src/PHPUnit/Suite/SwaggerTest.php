<?php


namespace Swaggest\JsonSchema\Tests\PHPUnit\Suite;


use Swaggest\JsonSchema\InvalidValue;
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
        } catch (InvalidValue $exception) {
            $failed = true;
            $this->assertEquals('No valid results for oneOf {
 0: No valid results for oneOf {
  0: No valid results for anyOf {
    0: Could not resolve #/definitions/Foo@: Foo at #->properties:paths->$ref[#/definitions/paths]->patternProperties[^/]:/pets->$ref[#/definitions/pathItem]->properties:get->$ref[#/definitions/operation]->properties:responses->$ref[#/definitions/responses]->patternProperties[^([0-9]{3})$|^(default)$]:200->$ref[#/definitions/responseValue]->oneOf[0]->$ref[#/definitions/response]->properties:schema->oneOf[0]->$ref[#/definitions/schema]->properties:items->anyOf[0]->$ref[#/definitions/schema]
    1: Array expected, {"$ref":"#\/definitions\/Foo"} received at #->properties:paths->$ref[#/definitions/paths]->patternProperties[^/]:/pets->$ref[#/definitions/pathItem]->properties:get->$ref[#/definitions/operation]->properties:responses->$ref[#/definitions/responses]->patternProperties[^([0-9]{3})$|^(default)$]:200->$ref[#/definitions/responseValue]->oneOf[0]->$ref[#/definitions/response]->properties:schema->oneOf[0]->$ref[#/definitions/schema]->properties:items->anyOf[1]
  } at #->properties:paths->$ref[#/definitions/paths]->patternProperties[^/]:/pets->$ref[#/definitions/pathItem]->properties:get->$ref[#/definitions/operation]->properties:responses->$ref[#/definitions/responses]->patternProperties[^([0-9]{3})$|^(default)$]:200->$ref[#/definitions/responseValue]->oneOf[0]->$ref[#/definitions/response]->properties:schema->oneOf[0]->$ref[#/definitions/schema]->properties:items
  1: Enum failed, enum: ["file"], data: "array" at #->properties:paths->$ref[#/definitions/paths]->patternProperties[^/]:/pets->$ref[#/definitions/pathItem]->properties:get->$ref[#/definitions/operation]->properties:responses->$ref[#/definitions/responses]->patternProperties[^([0-9]{3})$|^(default)$]:200->$ref[#/definitions/responseValue]->oneOf[0]->$ref[#/definitions/response]->properties:schema->oneOf[1]->$ref[#/definitions/fileSchema]->properties:type
 } at #->properties:paths->$ref[#/definitions/paths]->patternProperties[^/]:/pets->$ref[#/definitions/pathItem]->properties:get->$ref[#/definitions/operation]->properties:responses->$ref[#/definitions/responses]->patternProperties[^([0-9]{3})$|^(default)$]:200->$ref[#/definitions/responseValue]->oneOf[0]->$ref[#/definitions/response]->properties:schema
 1: Required property missing: $ref, data: {"description":"pet response","schema":{"type":"array","items":{"$ref":"#/definitions/Foo"}}} at #->properties:paths->$ref[#/definitions/paths]->patternProperties[^/]:/pets->$ref[#/definitions/pathItem]->properties:get->$ref[#/definitions/operation]->properties:responses->$ref[#/definitions/responses]->patternProperties[^([0-9]{3})$|^(default)$]:200->$ref[#/definitions/responseValue]->oneOf[1]->$ref[#/definitions/jsonReference]
} at #->properties:paths->$ref[#/definitions/paths]->patternProperties[^/]:/pets->$ref[#/definitions/pathItem]->properties:get->$ref[#/definitions/operation]->properties:responses->$ref[#/definitions/responses]->patternProperties[^([0-9]{3})$|^(default)$]:200->$ref[#/definitions/responseValue]',
                $exception->getMessage());
        }

        $this->assertTrue($failed);
    }

}