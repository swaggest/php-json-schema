<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit;



use Swaggest\JsonSchema\JsonSchema;

class ReImportTest extends \PHPUnit_Framework_TestCase
{

    public function testJsonSchema()
    {
        $data = file_get_contents(__DIR__ . '/../../../spec/json-schema.json');
        $data = json_decode($data);

        $schema = JsonSchema::importToSchema($data);
    }


    public function testDoubleImport()
    {
        $data = file_get_contents(__DIR__ . '/../../../spec/json-schema.json');
        $data = json_decode($data);
        //print_r($data);

        $schema = JsonSchema::importToSchema($data);
        //print_r(Properties::getFromSchema($schema)->enum);
        //$jsonSchema = $schema->import($data); // @todo fix the test
        //print_r($jsonSchema);
// #->properties:definitions->additionalProperties->properties:items->anyOf:0->$ref:#->properties:dependencies->additionalProperties

    }

}