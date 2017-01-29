<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Example;


use Swaggest\JsonSchema\Tests\Helper\Example;
use Swaggest\JsonSchema\Tests\Helper\Order;

class ExampleTest extends \PHPUnit_Framework_TestCase
{
    public function testExample()
    {
        $this->setExpectedException(get_class(new \Exception()));
        $example = new Example();
        $example->quantity = -1; // Exception: Minimum value exceeded

        $example = new Example();
        $example->quantity = -1; // Exception: Minimum value exceeded


        $order = new Order();
        $order->dateTime = new \DateTime();
        $example->orders[] = $order;

        Example::export($example);
    }

}