<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit;

use Swaggest\JsonSchema\Exception;
use Swaggest\JsonSchema\Schema;

class ArrayTest extends \PHPUnit_Framework_TestCase
{
    public function testItems()
    {
        $data = array(1, 2, 3);
        $schema = new Schema();
        $schema->additionalItems = Schema::integer();

        $this->assertSame($data, $schema->in($data));
    }

    public function testStdClass()
    {
        $json = '{"items":[{"type":"string"},{"type":"integer"}]}';
        $schemaData = json_decode($json);
        $schema = Schema::import($schemaData);
        $this->assertSame($json, json_encode($schema));
        $this->assertSame('string', $schema->items[0]->type);
        $this->assertSame('integer', $schema->items[1]->type);

        $schema->in(array("one", 2));

        $this->setExpectedException(get_class(new Exception()));
        $schema->in(array(1, "two"));
    }

}