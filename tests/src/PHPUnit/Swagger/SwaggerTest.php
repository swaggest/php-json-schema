<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Swagger;


use Swaggest\JsonSchema\Context;
use Swaggest\JsonSchema\RemoteRef\Preloaded;
use Swaggest\JsonSchema\SwaggerSchema\SwaggerSchema;

class SwaggerTest extends \PHPUnit_Framework_TestCase
{
    public function testReadSwaggerSchema()
    {
        $schemaData = json_decode(file_get_contents(__DIR__ . '/../../../../spec/swagger-schema.json'));

        $refProvider = new Preloaded();
        $refProvider->setSchemaData('http://swagger.io/v2/schema.json', $schemaData);

        $options = new Context();
        $options->setRemoteRefProvider($refProvider);

        $swaggerData = json_decode(file_get_contents(__DIR__ . '/../../../../spec/petstore-swagger.json'));
        $petStore = SwaggerSchema::import($swaggerData);

        $this->assertSame(
            '/pet:/pet/findByStatus:/pet/findByTags:/pet/{petId}:/pet/{petId}/uploadImage:/store/inventory:/store/order:/store/order/{orderId}:/user:/user/createWithArray:/user/createWithList:/user/login:/user/logout:/user/{username}',
            implode(':', array_keys($petStore->paths->toArray()))
        );
    }

}