<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit;



use Swaggest\JsonSchema\Context;
use Swaggest\JsonSchema\RemoteRef\Preloaded;
use Swaggest\JsonSchema\Schema;

class ReImportTest extends \PHPUnit_Framework_TestCase
{

    public function testJsonSchema()
    {
        $data = file_get_contents(__DIR__ . '/../../../spec/json-schema.json');
        $data = json_decode($data);

        $schema = Schema::import($data, new Context(new Preloaded()));
    }


    public function testDoubleImport()
    {
        $data = file_get_contents(__DIR__ . '/../../../spec/json-schema.json');
        $data = json_decode($data);
        //print_r($data);

        $schema = Schema::import($data, new Context(new Preloaded()));
        //print_r(Properties::getFromSchema($schema)->enum);
        $jsonSchema = $schema->in($data); // @todo fix the test
        //print_r($jsonSchema);
// #->properties:definitions->additionalProperties->properties:items->anyOf:0->$ref:#->properties:dependencies->additionalProperties

    }

}