<?php
namespace Swaggest\JsonSchema\Tests\PHPUnit\JsonSchema;

use Swaggest\JsonSchema\Context;
use Swaggest\JsonSchema\JsonSchema;
use Swaggest\JsonSchema\RemoteRef\Preloaded;
use Swaggest\JsonSchema\Schema;

class JsonSchemaTest extends \PHPUnit_Framework_TestCase
{
    public function testJsonSchema()
    {
        $schemaData = json_decode(file_get_contents(__DIR__ . '/../../../../spec/json-schema.json'));
        /** @var Schema $schema */
        $schema = Schema::import($schemaData, new Context(new Preloaded()));
        $this->assertSame('{"minimum":0,"type":"integer"}', json_encode($schema->properties->maxLength));
    }

}