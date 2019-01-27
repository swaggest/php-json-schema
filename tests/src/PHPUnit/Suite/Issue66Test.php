<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Suite;

use Swaggest\JsonSchema\Context;
use Swaggest\JsonSchema\RemoteRef\Preloaded;
use Swaggest\JsonSchema\Schema;

class Issue66Test extends \PHPUnit_Framework_TestCase
{
    public function testIssue()
    {
        $schemaPath = realpath(__DIR__ . '/../../../resources/suite/issue66.json');
        $schemaData = json_decode(file_get_contents($schemaPath));
        $resolver = new Preloaded();
        $resolver->setSchemaData($schemaPath, $schemaData);

        $options = new Context($resolver);

        $schema = Schema::import((object)['$ref' => $schemaPath], $options);
        $res = $schema->in(json_decode('{"confirmed":{"count":123, "_type": "example_item"}, "to_pay":{"count":123, "_type": "example_item"}}'));
        $this->assertSame(123, $res->confirmed->count);
    }

    public function testDirectImport()
    {
        $schemaPath = realpath(__DIR__ . '/../../../resources/suite/issue66.json');
        $schema = Schema::import($schemaPath);
        $res = $schema->in(json_decode('{"confirmed":{"count":123, "_type": "example_item"}, "to_pay":{"count":123, "_type": "example_item"}}'));
        $this->assertSame(123, $res->confirmed->count);
    }

}