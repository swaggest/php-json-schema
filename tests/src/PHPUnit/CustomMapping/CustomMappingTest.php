<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\CustomMapping;


use Swaggest\JsonSchema\Context;
use Swaggest\JsonSchema\SwaggerSchema\Schema;
use Swaggest\JsonSchema\SwaggerSchema\SwaggerSchema;
use Swaggest\JsonSchema\Tests\Helper\CustomSchema;
use Swaggest\JsonSchema\Tests\Helper\CustomSwaggerSchema;

class CustomMappingTest extends \PHPUnit_Framework_TestCase
{
    public function testMapping() {
        $schema = CustomSwaggerSchema::import(json_decode(
            file_get_contents(__DIR__ . '/../../../../spec/petstore-swagger.json')
        ));

        $this->assertInstanceOf(CustomSchema::className(), $schema->definitions['User']);
    }

    public function testMappingWithContext() {
        $context = new Context();
        $context->objectItemClassMapping[Schema::className()] = CustomSchema::className();
        $schema = SwaggerSchema::schema()->in(json_decode(
            file_get_contents(__DIR__ . '/../../../../spec/petstore-swagger.json')
        ), $context);
        $this->assertInstanceOf(CustomSchema::className(), $schema->definitions['User']);

    }

}