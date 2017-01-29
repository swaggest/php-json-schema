<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Example;


use Swaggest\JsonSchema\Exception\NumericException;
use Swaggest\JsonSchema\Exception\ObjectException;
use Swaggest\JsonSchema\SchemaLoader;
use Swaggest\JsonSchema\Tests\Helper\Example;
use Swaggest\JsonSchema\Tests\Helper\Order;

class ExampleTest extends \PHPUnit_Framework_TestCase
{

    public function testJsonSchema()
    {
        $this->setExpectedException(get_class(new ObjectException()),
            'Required property missing: id at #->properties:orders->items[1]->#/definitions/order'
        );

        $schemaJson = <<<'JSON'
{
    "type": "object",
    "properties": {
        "id": {
            "type": "integer"
        },
        "name": {
            "type": "string"
        },
        "orders": {
            "type": "array",
            "items": {
                "$ref": "#/definitions/order"
            }
        }
    },
    "required":["id"],
    "definitions": {
        "order": {
            "type": "object",
            "properties": {
                "id": {
                    "type": "integer"
                },
                "price": {
                    "type": "number"
                },
                "updated": {
                    "type": "string",
                    "format": "date-time"
                }
            },
            "required":["id"]
        }
    }
}
JSON;

        $schema = SchemaLoader::create()->readSchema(json_decode($schemaJson));
        $schema->import(json_decode(<<<'JSON'
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
        )); // Exception: Required property missing: id at #->properties:orders->items[1]->#/definitions/order
    }

    public function testEarlyValidation()
    {
        $this->setExpectedException(get_class(new NumericException()), 'Value more than 0 expected, -1 received', NumericException::MINIMUM);
        $example = new Example();
        $example->quantity = -1; // Exception: Value more than 0 expected, -1 received
    }

    public function testMissingRequiredProperty()
    {
        $this->setExpectedException(get_class(new ObjectException()), 'Required property missing: id');

        $example = new Example();
        $example->quantity = 10;
        Example::export($example); // Exception: Required property missing: id
    }

    public function testExample()
    {
        $this->setExpectedException(get_class(new ObjectException()), 'Required property missing: id at #->properties:orders->items[0]');

        $example = new Example();
        $example->id = 1;
        $example->name = 'John Doe';

        $order = new Order();
        $order->dateTime = '2015-10-28T07:28:00Z';
        $example->orders[] = $order;

        Example::export($example); // Exception: Required property missing: id at #->properties:orders->items[0]
    }

}