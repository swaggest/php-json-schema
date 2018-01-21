<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Example;


use Swaggest\JsonSchema\Context;
use Swaggest\JsonSchema\Exception\NumericException;
use Swaggest\JsonSchema\Exception\ObjectException;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\Composition;
use Swaggest\JsonSchema\Tests\Helper\Order;
use Swaggest\JsonSchema\Tests\Helper\User;
use Swaggest\JsonSchema\Tests\Helper\UserInfo;

class ExampleTest extends \PHPUnit_Framework_TestCase
{

    public function testJsonSchema()
    {
        $this->setExpectedException(get_class(new ObjectException()),
            'Required property missing: id at #->properties:orders->items[1]'
        );

        $schemaJson = <<<'JSON'
{
    "type": "object",
    "x-custom-data": "Custom Value",
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

        $schema = Schema::import(json_decode($schemaJson));
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
        )); // Exception: Required property missing: id at #->properties:orders->items[1]->#/definitions/order
    }

    public function testEarlyValidation()
    {
        $this->setExpectedException(get_class(new NumericException()), 'Value more than 0 expected, -1 received', NumericException::MINIMUM);
        $example = new User();
        $example->quantity = -1; // Exception: Value more than 0 expected, -1 received
    }

    public function testMissingRequiredProperty()
    {
        $this->setExpectedException(get_class(new ObjectException()), 'Required property missing: id');

        $example = new User();
        $example->quantity = 10;
        User::export($example); // Exception: Required property missing: id
    }

    public function testExample()
    {
        $example = new User();
        $example->id = 1;
        $example->name = 'John Doe';

        $order = new Order();
        $order->dateTime = '2015-10-28T07:28:00Z';
        $example->orders[] = $order;

        $this->setExpectedException(get_class(new ObjectException()), 'Required property missing: id at #->properties:orders->items[0]');
        /** @noinspection PhpUnhandledExceptionInspection */
        User::export($example); // Exception: Required property missing: id at #->properties:orders->items[0]
    }

    public function testNameMapper()
    {

        $order = new Order();
        $order->id = 1;
        $order->dateTime = '2015-10-28T07:28:00Z';
        $order->price = 2.2;
        /** @noinspection PhpUnhandledExceptionInspection */
        $exported = Order::export($order);
        $json = <<<JSON
{
    "id": 1,
    "date_time": "2015-10-28T07:28:00Z",
    "price": 2.2
}
JSON;
        $this->assertSame($json, json_encode($exported, JSON_PRETTY_PRINT));

        $imported = Order::import(json_decode($json));
        $this->assertSame(1, $imported->id);
        $this->assertSame('2015-10-28T07:28:00Z', $imported->dateTime);
        $this->assertSame(2.2, $imported->price);

        $options = new Context();
        $options->mapping = Order::FANCY_MAPPING;

        /** @noinspection PhpUnhandledExceptionInspection */
        $exported = Order::export($order, $options);
        $json = <<<JSON
{
    "Id": 1,
    "DaTe_TiMe": "2015-10-28T07:28:00Z",
    "PrIcE": 2.2
}
JSON;
        $this->assertSame($json, json_encode($exported, JSON_PRETTY_PRINT));

        $imported = Order::import(json_decode($json), $options);
        $this->assertSame('2015-10-28T07:28:00Z', $imported->dateTime);
    }


    public function testNestedStructure()
    {
        $user = new User();
        $user->id = 1;

        $info = new UserInfo();
        $info->firstName = 'John';
        $info->lastName = 'Doe';
        $info->birthDay = '1970-01-01T00:00:00Z';
        $user->info = $info;

        $json = <<<JSON
{
    "id": 1,
    "firstName": "John",
    "lastName": "Doe",
    "birthDay": "1970-01-01T00:00:00Z"
}
JSON;
        $exported = User::export($user);
        $this->assertSame($json, json_encode($exported, JSON_PRETTY_PRINT));

        $imported = User::import(json_decode($json));
        $this->assertSame(1, $imported->id);

        $this->assertSame(1, $imported->info->id);
        $this->assertSame('John', $imported->info->firstName);
        $this->assertSame('Doe', $imported->info->lastName);
    }

    public function testNestedComposition()
    {
        $schema = new Composition(UserInfo::schema(), Order::schema());
        $json = <<<JSON
{
    "id": 1,
    "firstName": "John",
    "lastName": "Doe",
    "price": 2.66
}
JSON;
        $object = $schema->in(json_decode($json));

        // Get particular object with `pick` accessor
        $info = UserInfo::pick($object);
        $order = Order::pick($object);

        // Data is imported objects of according classes
        $this->assertTrue($order instanceof Order);
        $this->assertTrue($info instanceof UserInfo);

        $this->assertSame(1, $order->id);
        $this->assertSame('John', $info->firstName);
        $this->assertSame('Doe', $info->lastName);
        $this->assertSame(2.66, $order->price);
    }
}