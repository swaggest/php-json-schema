<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\ClassStructure;


use Swaggest\JsonSchema\Structure\Composition;
use Swaggest\JsonSchema\Tests\Helper\LevelThreeClass;
use Swaggest\JsonSchema\Tests\Helper\NestedStructure;
use Swaggest\JsonSchema\Tests\Helper\SampleStructure;

class NestedTest extends \PHPUnit_Framework_TestCase
{
    public function testClassStructure()
    {
        $data = json_decode(<<<JSON
{
    "ownString": "aaa",
    "ownMagicInt": 1,
    "native": true,
    "propOne": "bbb"
}
JSON
        );
        $object = NestedStructure::import($data);
        $this->assertSame('aaa', $object->ownString);
        $this->assertSame(true, $object->sampleNested->native);
        $this->assertSame('bbb', $object->sampleNested->propOne);

        $data2 = NestedStructure::export($object);
        $this->assertEquals((array)$data, (array)$data2);

        $object->sampleNested->propOne = 'ccc';
        $this->assertSame('ccc', $object->propOne);
    }


    public function testDynamic()
    {
        $schema = new Composition(SampleStructure::schema(), LevelThreeClass::schema());

        $data = json_decode(<<<JSON
{
    "ownString": "aaa",
    "ownMagicInt": 1,
    "native": true,
    "propOne": "bbb",
    "level3": 3
}
JSON
        );

        $object = $schema->in($data);

        $this->assertSame('aaa', $object->ownString);
        $this->assertSame(1, $object->ownMagicInt);
        $this->assertSame(3, $object->level3); // flat accessor
        $this->assertSame(true, $object->native);

        $sample = SampleStructure::pick($object);
        $l3 = LevelThreeClass::pick($object);

        $this->assertSame('bbb', $sample->propOne);
        $this->assertSame(true, $sample->native);

        $this->assertSame(3, $l3->level3);

        $l3->level3 = 2;
        $this->assertSame(2, $object->level3); // flat accessor

        $object->level3 = 5;
        $this->assertSame(5, $l3->level3);

        $sample->propTwo = 8;

        $data2 = $schema->out($object);
        $this->assertEquals(array(
            'ownString' => 'aaa',
            'ownMagicInt' => 1,
            'native' => true,
            'propOne' => 'bbb',
            'level3' => 5,
            'propTwo' => 8,
        ), (array)$data2);
    }

}