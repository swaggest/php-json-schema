<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Schema;

use Swaggest\JsonSchema\Schema;

class CreateTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $schema = Schema::create()
            ->setProperty('stringValue', Schema::string())
            ->setProperty('one', Schema::create()
                ->setProperty('two', Schema::create()
                    ->setProperty('three', Schema::number())
                )
            );

        $rawData = (object)array(
            'stringValue' => 'abc',
            'one' => (object)array(
                'two' => (object)array(
                    'three' => 3
                ),
            ),
        );
        $data = $schema->import($rawData);

        $this->assertSame('abc', $data->stringValue);
        $this->assertSame(3, $data->one->two->three);

        $rawDataTwo = $schema->export($data);
        $this->assertEquals($rawData, $rawDataTwo);
    }

}