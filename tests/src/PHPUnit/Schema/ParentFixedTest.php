<?php

namespace Yaoi\Schema\Tests\PHPUnit\Schema;


use Yaoi\Schema\Tests\Helper\LevelOneClass;

class ParentFixedTest extends ParentTest
{
    /**
     * @return \Yaoi\Schema\OldSchema|LevelOneClass
     */
    protected function deepSchema()
    {
        $schema = LevelOneClass::makeSchema();
        return $schema;
    }

    public function testImportClass()
    {
        $data = (object)array(
            'level1' => (object)array(
                'level2' => (object)array(
                    'level3' => 123 // integer required
                ),
            ),
        );
        $object = LevelOneClass::import($data);
        $this->assertSame(123, $object->level1->level2->level3);
        $this->assertEquals($data, LevelOneClass::export($object));
    }


}