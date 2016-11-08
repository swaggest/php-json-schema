<?php

namespace Yaoi\Schema\Tests\PHPUnit;

use Yaoi\Schema\OldConstraint\Items;
use Yaoi\Schema\OldSchema;
use Yaoi\Schema\Types\ArrayType;
use Yaoi\Schema\Types\IntegerType;
use Yaoi\Schema\Types\StringType;

class ArrayTest extends \PHPUnit_Framework_TestCase
{
    public function testItems()
    {
        $data = array(1, 2, 3);
        $schema = ArrayType::makeSchema(new Items(IntegerType::makeSchema()));
        $this->assertSame($data, $schema->import($data));
    }

    public function testItemsInvalid()
    {
        $data = array(1, 2, 3);
        $schema = ArrayType::makeSchema(new Items(StringType::makeSchema()));
        $this->assertSame($data, $schema->import($data));
    }

}