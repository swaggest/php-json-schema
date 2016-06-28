<?php

namespace Yaoi\Schema\Tests\Schema;


use Yaoi\Schema\Tests\Helper\LevelOneClass;

class ParentFixedTest extends ParentTest
{
    /**
     * @return \Yaoi\Schema\Schema|LevelOneClass
     */
    protected function deepSchema()
    {
        $schema = LevelOneClass::makeSchema();
        return $schema;
    }

    public function testImport()
    {
        $object = $this->deepSchema()->import(array(
            'level1' => array(
                'level2' => array(
                    'level3' => 123 // integer required
                ),
            ),
        ));
        $this->assertSame(123, $object->level1->level2->level3);
    }


}