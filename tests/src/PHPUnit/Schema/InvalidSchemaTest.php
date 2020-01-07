<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Schema;

use Swaggest\JsonSchema\Schema;

class InvalidSchemaTest extends \PHPUnit_Framework_TestCase
{

    public function testValidationFailedWithInvalidSchema()
    {
        $this->setExpectedException('Swaggest\JsonSchema\Exception');
        $data = __DIR__ . '/../../../resources/invalid_json.json';
        $schema = Schema::import($data);
        $schema->in(json_decode(<<<'JSON'
{
    "id": 1,
    "name":"John Doe",
    "orders":[
        {
            "id":1
        },
        {
            "price":1.0
        }
    ]
}
JSON
        ));
    }

}
