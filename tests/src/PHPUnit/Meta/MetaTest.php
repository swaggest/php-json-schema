<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Meta;

use Swaggest\JsonSchema\Tests\Helper\DbId;
use Swaggest\JsonSchema\Tests\Helper\DbTable;
use Swaggest\JsonSchema\Tests\Helper\Order;

class MetaTest extends \PHPUnit_Framework_TestCase
{
    public function testMeta()
    {
        $dbId = DbId::get(Order::properties()->userId);
        $this->assertSame('users', $dbId->table->tableName);

        $dbTable = DbTable::get(Order::schema());
        $this->assertSame('orders', $dbTable->tableName);
    }
}