<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\ClassStructure;

use Swaggest\JsonSchema\Exception\StringException;
use Swaggest\JsonSchema\Exception\TypeException;
use Swaggest\JsonSchema\Tests\Helper\ClassWithAllOf;
use Swaggest\JsonSchema\Tests\Helper\LevelThreeClass;
use Swaggest\JsonSchema\Tests\Helper\SampleProperties;
use Swaggest\JsonSchema\Tests\Helper\SampleStructure;
use Swaggest\JsonSchema\Tests\Helper\StructureWithItems;

class ClassStructureTest extends \PHPUnit_Framework_TestCase
{
    public function testSample()
    {
        $schema = SampleStructure::schema();

        $object = $schema->in((object)array(
            'propOne' => '1',
            'propTwo' => 2,
            'recursion' => (object)array(
                'propOne' => '11',
                'propTwo' => 22,
            )
        ));

        $this->assertInstanceOf(get_class(new SampleStructure()), $object);

        $this->assertSame('1', $object->propOne);
        $this->assertSame(2, $object->propTwo);

        $this->assertSame('11', $object->recursion->propOne);
        $this->assertSame(22, $object->recursion->propTwo);

        $exported = $schema->out($object);
        $this->assertSame('{"propOne":"1","propTwo":2,"recursion":{"propOne":"11","propTwo":22}}', json_encode($exported));
    }


    public function testItems()
    {
        $json = '{"list":[{"level3":1},{"level3":2},{"level3":3}]}';
        $data = json_decode($json);
        $imported = StructureWithItems::import($data);
        $this->assertSame(1, $imported->list[0]->level3);
        $this->assertSame(2, $imported->list[1]->level3);
        $this->assertSame(3, $imported->list[2]->level3);

        $exported = StructureWithItems::export($imported);
        $this->assertSame($json, json_encode($exported));
    }

    public function testItems2()
    {
        $object = new StructureWithItems();

        $l = new LevelThreeClass();
        $l->level3 = 1;
        $object->list[] = $l;

        $l = new LevelThreeClass();
        $l->level3 = 2;
        $object->list[] = $l;

        $l = new LevelThreeClass();
        $l->level3 = 3;
        $object->list[] = $l;

        $json = '{"list":[{"level3":1},{"level3":2},{"level3":3}]}';
        $exported = StructureWithItems::export($object);
        $this->assertTrue($exported->list[0] instanceof \stdClass, 'Exported item is not \stdClass');
        $this->assertTrue($exported->list[1] instanceof \stdClass, 'Exported item is not \stdClass');
        $this->assertTrue($exported->list[2] instanceof \stdClass, 'Exported item is not \stdClass');
        $this->assertSame($json, json_encode($exported));
    }


    public function testSampleInvalid()
    {
        $schema = SampleStructure::schema();
        $this->setExpectedException(get_class(new TypeException()), 'String expected, 11 received at #->$ref[#/definitions/Swaggest\JsonSchema\Tests\Helper\SampleStructure]->properties:recursion->$ref[#/definitions/Swaggest\JsonSchema\Tests\Helper\SampleStructure]->properties:propOne');
        $schema->in((object)array(
            'propOne' => '1',
            'propTwo' => 2,
            'recursion' => (object)array(
                'propOne' => 11,
                'propTwo' => 22,
            )
        ));
    }

    public function testAllOfClassInstance()
    {
        $value = ClassWithAllOf::import((object)array('myProperty'=>'abc'));
        $this->assertSame('abc', $value->myProperty);
        $this->assertTrue($value instanceof ClassWithAllOf);
    }

    public function testAdditionalProperties()
    {
        $properties = new SampleProperties();
        $properties->setAdditionalPropertyValue('propOne', (object)array(
            'subOne' => 'one',
            'subTwo' => 'two'
        ));

        /** @noinspection PhpUnhandledExceptionInspection */
        $exported = SampleProperties::export($properties);
        $this->assertSame('{}', json_encode($exported, JSON_PRETTY_PRINT), 'With flag to false');


        $properties->setExtendedPropertySerialization();
        $exported = SampleProperties::export($properties);
        $json = <<<JSON
{
    "propOne": {
        "subOne": "one",
        "subTwo": "two"
    }
}
JSON;
        $this->assertSame($json, json_encode($exported, JSON_PRETTY_PRINT), 'With flag to true');
    }

    public function testPatternProperties()
    {
        $properties = new SampleProperties();
        $properties->setXValue('x-foo', 'bar');
        $properties->setXValue('x-baz', 'gnu');

        /** @noinspection PhpUnhandledExceptionInspection */
        $exported = SampleProperties::export($properties);
        $this->assertSame('{}', json_encode($exported, JSON_PRETTY_PRINT), 'With flag to false');

        $properties->setExtendedPropertySerialization();
        $exported = SampleProperties::export($properties);
        $json = <<<JSON
{
    "x-foo": "bar",
    "x-baz": "gnu"
}
JSON;
        $this->assertSame($json, json_encode($exported, JSON_PRETTY_PRINT), 'With flag to true');
    }

    /**
     * @expectedException        Swaggest\JsonSchema\Exception\StringException
     * @expectedExceptionMessage Pattern mismatch
     */
    public function testPatternPropertiesMismatch()
    {
        $properties = new SampleProperties();
        $properties->setXValue('xfoo', 'bar');
    }

}