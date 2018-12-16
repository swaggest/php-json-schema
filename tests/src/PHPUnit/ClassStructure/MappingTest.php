<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\ClassStructure;


use Swaggest\JsonSchema\Context;
use Swaggest\JsonSchema\Tests\Helper\Order;

class MappingTest extends \PHPUnit_Framework_TestCase
{
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


        /** @noinspection PhpUnhandledExceptionInspection */
        $imported = Order::import(json_decode($json));
        $this->assertSame(1, $imported->id);
        $this->assertSame('2015-10-28T07:28:00Z', $imported->dateTime);
        $this->assertSame(2.2, $imported->price);

    }

    /**
     * @throws \Swaggest\JsonSchema\Exception
     * @throws \Swaggest\JsonSchema\InvalidValue
     */
    public function testNameMapperNonDefault()
    {
        $order = new Order();
        $order->id = 1;
        $order->dateTime = '2015-10-28T07:28:00Z';
        $order->price = 2.2;

        $options = new Context();
        $options->mapping = Order::FANCY_MAPPING;

        Order::schema();

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


}