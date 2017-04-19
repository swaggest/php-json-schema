<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Swagger;


use Swaggest\JsonSchema\JsonSchema;
use Swaggest\JsonSchema\ProcessingOptions;
use Swaggest\JsonSchema\RemoteRef\Preloaded;

class SwaggerTest extends \PHPUnit_Framework_TestCase
{
    public function testReadSwaggerSchema()
    {
        $schemaData = json_decode(file_get_contents(__DIR__ . '/../../../../spec/swagger-schema.json'));


        $refProvider = new Preloaded();
        $refProvider->setSchemaData('http://swagger.io/v2/schema.json', $schemaData);

        $options = new ProcessingOptions();
        $options->setRemoteRefProvider($refProvider);


        $schema = JsonSchema::importToSchema($schemaData, $options);
    }

}