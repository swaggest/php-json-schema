<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\ClassStructure;


use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;
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


    public function testAdvanced()
    {
        $input = <<<JSON
{
    "baseAttr1": "baseAttr1",
    "baseAttr2": 1,
    "typeSpecific": {
        "child2Attr": "child2Attr"
    }
}
JSON;

        $imported = ParentObject::import(json_decode($input));
        echo get_class($imported); // = ParentObject but expecting ChildObject2
    }
}

class ParentObject extends ClassStructure
{
    /**
     * @param \Swaggest\JsonSchema\Constraint\Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $s1 = ChildObject1::schema();
        $s2 = ChildObject2::schema();

        $ownerSchema->oneOf = [
            $s1->nested(),
            $s2->nested()
        ];

//        $properties->__set($s1->objectItemClass, $s1->nested());
//        $properties->__set($s2->objectItemClass, $s2->nested());


        $ownerSchema->setFromRef(self::className());
        // remove comment to receive Swaggest\JsonSchema\Exception\ObjectException: Additional properties not allowed
        // $ownerSchema->additionalProperties = false;
    }
}

class BaseObject extends ClassStructure
{
    public $baseAttr1;
    public $baseAttr2;

    /**
     * @param \Swaggest\JsonSchema\Constraint\Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $properties->baseAttr1 = Schema::string();
        $properties->baseAttr2 = Schema::integer();

        $ownerSchema->type = 'object';
        $ownerSchema->setFromRef(self::className());
        $ownerSchema->required = [
            self::names()->baseAttr1
        ];
        $ownerSchema->additionalProperties = false;
    }
}

class ChildObject1 extends BaseObject
{
    public $typeSpecific;

    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        parent::setUpProperties($properties, $ownerSchema);
        $properties->typeSpecific = ChildObject1TypeSpecific::schema();
        $ownerSchema->required[] = self::names()->typeSpecific;
    }
}

class ChildObject1TypeSpecific extends ClassStructure
{
    public $child1Attr;

    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $properties->child1Attr = Schema::integer();

        $ownerSchema->type = 'object';
        $ownerSchema->setFromRef(self::className());
        $ownerSchema->required = [
            self::names()->child1Attr
        ];
        $ownerSchema->additionalProperties = true;
    }
}

class ChildObject2 extends BaseObject
{
    public $typeSpecific;

    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        parent::setUpProperties($properties, $ownerSchema);
        $properties->typeSpecific = ChildObject2TypeSpecific::schema();
        $ownerSchema->required[] = self::names()->typeSpecific;
    }
}

class ChildObject2TypeSpecific extends ClassStructure
{
    public $child2Attr;

    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $properties->child2Attr = Schema::string();

        $ownerSchema->type = 'object';
        $ownerSchema->setFromRef(self::className());
        $ownerSchema->required = [
            self::names()->child2Attr
        ];
        $ownerSchema->additionalProperties = true;
    }
}


