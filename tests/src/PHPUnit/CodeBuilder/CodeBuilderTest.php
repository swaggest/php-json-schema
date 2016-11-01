<?php

namespace Yaoi\Schema\Tests\PHPUnit\CodeBuilder;

use Yaoi\Schema\CodeBuilder\PHPCodeBuilder;
use Yaoi\Schema\Schema;

class CodeBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testJsonSchema()
    {
        $data = file_get_contents(__DIR__ . '/../../../res/json-schema.json');
        $data = json_decode($data, true);
        //print_r($data);

        $schema = new Schema($data);
        $codeBuilder = new PHPCodeBuilder();
        $codeBuilder->namespace = '\Yaoi\Schema\Tests\Helper\JsonSchema';
        $codeBuilder->rootClassName = 'JsonSchema';

        $instantiationCode =  $codeBuilder->getSchemaInstantiationCode($schema);
        $this->assertSame('JsonSchema::create()', $instantiationCode);
        $codeBuilder->storeToDisk(__DIR__ . '/../Helper/');
    }
}