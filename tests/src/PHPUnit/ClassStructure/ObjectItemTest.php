<?php

namespace PHPUnit\ClassStructure;


use Swaggest\JsonSchema\Structure\ObjectItem;

class ObjectItemTest extends \PHPUnit_Framework_TestCase
{
    public function testIndirectIsset()
    {
        $o = new ObjectItem();
        $this->assertSame(false, isset($o->a));
        $this->assertSame(false, isset($o->a->b));

        $o->a = (object)array();
        $this->assertSame(true, isset($o->a));
        $this->assertSame(false, isset($o->a->b));

        $o->a->b = 1;
        $this->assertSame(true, isset($o->a->b));
    }

}