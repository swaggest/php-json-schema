<?php

namespace Yaoi\Schema\Tests;


use Yaoi\Schema\Properties;
use Yaoi\Schema\Schema;

class Test extends \PHPUnit_Framework_TestCase
{

    public function testJsonSchema()
    {
        $data = file_get_contents(__DIR__ . '/../../res/json-schema.json');
        $data = json_decode($data, true);
        //print_r($data);

        $schema = new Schema($data);
        //print_r($schema->constraints[Properties::className()]->properties['definitions']);

        $jsonSchema = $schema->import($data);
        print_r($jsonSchema);
    }

}