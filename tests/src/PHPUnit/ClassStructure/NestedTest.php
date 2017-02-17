<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\ClassStructure;


use Swaggest\JsonSchema\Tests\Helper\NestedStructure;
use Swaggest\JsonSchema\Tests\Helper\SampleStructure;

class NestedTest extends \PHPUnit_Framework_TestCase
{
    public function testClassStructure()
    {
        $schema = NestedStructure::schema();
        $data = json_decode(<<<JSON
{
    "ownString": "aaa",
    "ownMagicInt": 1,
    "native": true
}
JSON
);
        $object = $schema->import($data);
        $this->assertSame('aaa', $object->ownString);
        $this->assertSame(true, $object->getNested(SampleStructure::class)->native);
    }

}