<?php

namespace Yaoi\Schema\Tests\PHPUnit;

use Yaoi\Schema\NG\Schema;

class ArrayTest extends \PHPUnit_Framework_TestCase
{
    public function testItems()
    {
        $data = array(1, 2, 3);
        $schema = new Schema();
        $schema->items = Schema::integer();

        $this->assertSame($data, $schema->import($data));
    }

}