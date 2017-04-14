<?php
namespace Swaggest\JsonSchema\Tests\PHPUnit\JsonSchema;

use Swaggest\JsonSchema\JsonSchema;
use Swaggest\JsonSchema\ProcessingOptions;
use Swaggest\JsonSchema\RemoteRef\Preloaded;

class JsonSchemaTest extends \PHPUnit_Framework_TestCase
{
    public function testJsonSchema()
    {
        $schemaData = json_decode(file_get_contents(__DIR__ . '/../../../../spec/json-schema.json'));
        /** @var JsonSchema $schema */
        $schema = JsonSchema::importToSchema($schemaData, new ProcessingOptions(new Preloaded()));
        $this->assertSame('{"minimum":0,"type":"integer"}', json_encode($schema->properties->maxLength));
    }

}