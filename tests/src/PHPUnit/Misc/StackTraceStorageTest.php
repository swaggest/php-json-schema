<?php

namespace Yaoi\Schema\Tests\PHPUnit\Misc;

use Yaoi\Schema\NG\StackTraceStorage;

class StackTraceStorageTest extends \PHPUnit_Framework_TestCase
{
    public function testInclusiveSimple()
    {
        $stack = new StackTraceStorage();

        $stack->push();

        $stack->addData(1);
        $stack->addData(2);

        $frame = $stack->pop(true);

        $this->assertSame(array(1, 2), $frame);
    }


    public function testExclusiveSimple()
    {
        $stack = new StackTraceStorage();

        $stack->push();

        $stack->addData(1);
        $stack->addData(2);

        $frame = $stack->pop();

        $this->assertSame(array(1, 2), $frame);
    }

    public function testExclusiveSimpleKey()
    {
        $stack = new StackTraceStorage();

        $stack->push();

        $stack->addUnique('one', 1);
        $stack->addUnique('two', 2);

        $frame = $stack->pop();

        $this->assertSame(array('one' => 1, 'two' => 2), $frame);
    }

    public function testInclusiveSimpleKey()
    {
        $stack = new StackTraceStorage();

        $stack->push();

        $stack->addUnique('one', 1);
        $stack->addUnique('two', 2);

        $frame = $stack->pop();

        $this->assertSame(array('one' => 1, 'two' => 2), $frame);
    }

    public function testExclusiveNestedKey()
    {
        $stack = new StackTraceStorage();

        $stack->push();

        $stack->addUnique('one', 1);
        $stack->addUnique('two', 2);

        $stack->push();

        $stack->addUnique('three', 3);
        $stack->addUnique('four', 4);

        $frame = $stack->pop();
        $this->assertSame(array('three' => 3, 'four' => 4), $frame);

        $frame = $stack->pop();
        $this->assertSame(array('one' => 1, 'two' => 2), $frame);
    }

    public function testInclusiveNestedKey()
    {
        $stack = new StackTraceStorage();

        $stack->push();

        $stack->addUnique('one', 1);
        $stack->addUnique('two', 2);

        $stack->push();

        $stack->addUnique('three', 3);
        $stack->addUnique('four', 4);

        $frame = $stack->pop(true);
        $this->assertSame(array('three' => 3, 'four' => 4), $frame);

        $frame = $stack->pop(true);
        $this->assertSame(array('one' => 1, 'two' => 2, 'three' => 3, 'four' => 4), $frame);
    }

}