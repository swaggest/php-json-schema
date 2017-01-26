<?php

namespace Yaoi\Schema\Tests\PHPUnit;


use Yaoi\Schema\SchemaLoader;

class ReImportTest extends \PHPUnit_Framework_TestCase
{

    public function testJsonSchema()
    {
        $data = file_get_contents(__DIR__ . '/../../../spec/json-schema.json');
        $data = json_decode($data);
        //print_r($data);

        $schema = SchemaLoader::create()->readSchema($data);
        //print_r($schema);
        //print_r($schema->constraints[Properties::className()]->properties['definitions']);
    }


    public function testDoubleImport()
    {
        $data = file_get_contents(__DIR__ . '/../../../spec/json-schema.json');
        $data = json_decode($data);
        //print_r($data);

        $schema = SchemaLoader::create()->readSchema($data);
        //print_r(Properties::getFromSchema($schema)->enum);
        $jsonSchema = $schema->import($data);
        //print_r($jsonSchema);

    }

}