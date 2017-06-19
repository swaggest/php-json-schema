<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Constraint;


use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\Schema;

class DependenciesTest extends \PHPUnit_Framework_TestCase
{
    public function testSubschemaDeps()
    {
        $schemaJson = <<<JSON
{
    "dependencies": {
        "bar": {
            "properties": {
                "foo": {"type": "integer"},
                "bar": {"type": "integer"}
            }
        }
    }
}
JSON;
        $dataJson = <<<JSON
{"foo": 1, "bar": 2}
JSON;


        $schema = Schema::import(json_decode($schemaJson));
        $imported = $schema->in(json_decode($dataJson));
        $this->assertSame(1, $imported->foo);
        $this->assertSame(2, $imported->bar);
    }

    public function testMissingDependencies() {
        $schemaJson = <<<JSON
{
    "dependencies": {
        "bar": [
            "foo"
        ]
    }
}
JSON;
        $dataJson = <<<JSON
{
    "bar": 2
}
JSON;


        $schema = Schema::import(json_decode($schemaJson));
        $this->setExpectedException(get_class(new InvalidValue()));
        $schema->in(json_decode($dataJson));
    }

}