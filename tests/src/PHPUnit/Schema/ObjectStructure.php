<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Schema;


use Swaggest\JsonSchema\Schema;

class ObjectStructure extends \PHPUnit_Framework_TestCase
{

    public function testObjectAsArray()
    {
        $schema = new Schema();
        $schema->setUseObjectAsArray(true);

        $data = json_decode('{"one":1,"two":2,"three":3}');

        $imported = $schema->in($data);
        $this->assertSame(array("one" => 1, "two" => 2, "three" => 3), $imported);
        $exported = $schema->out($imported);
        $this->assertTrue($exported instanceof \stdClass, '\stdClass expected on export');
        $this->assertSame(array("one" => 1, "two" => 2, "three" => 3), (array)$exported);
    }

}