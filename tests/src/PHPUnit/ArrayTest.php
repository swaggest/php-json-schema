<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit;

use Swaggest\JsonSchema\Schema;

class ArrayTest extends \PHPUnit_Framework_TestCase
{
    public function testItems()
    {
        $data = array(1, 2, 3);
        $schema = new Schema();
        $schema->additionalItems = Schema::integer();

        $this->assertSame($data, $schema->import($data));
    }

}