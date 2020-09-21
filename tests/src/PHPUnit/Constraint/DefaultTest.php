<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Constraint;

use Swaggest\JsonSchema\Schema;

class DefaultTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @throws \Exception
     * @throws \Swaggest\JsonSchema\Exception
     * @throws \Swaggest\JsonSchema\InvalidValue
     */
    public function testValidDefault()
    {
        $schema = Schema::import(json_decode(<<<'JSON'
{
    "properties": {
        "foo": {
            "type": "integer",
            "default": []
        }
    }
}

JSON
));
        $data = $schema->in(new \stdClass());
        $this->assertSame([], $data->foo);
    }

    /**
     * @throws \Exception
     * @throws \Swaggest\JsonSchema\Exception
     * @throws \Swaggest\JsonSchema\InvalidValue
     */
    public function testNullDefault()
    {
        $schema = Schema::import(json_decode(<<<'JSON'
{
    "properties": {
        "foo": {
            "type": ["null", "integer"],
            "default": null
        }
    }
}

JSON
        ));
        $data = $schema->in(new \stdClass());
        $this->assertSame('{"foo":null}', json_encode($data));
    }

}