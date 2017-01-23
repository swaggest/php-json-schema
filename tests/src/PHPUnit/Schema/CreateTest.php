<?php

namespace Yaoi\Schema\Tests\PHPUnit\Schema;

use Yaoi\Schema\Constraint\Ref;
use Yaoi\Schema\Constraint\Type;
use Yaoi\Schema\Constraint\Properties;
use Yaoi\Schema\Schema;

class CreateTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $schema = new Schema();

        $properties = new Properties();
        $properties['stringValue'] = new Type('string');

        $properties['one'] = new Schema();
        $properties['one']->properties = Properties::create()
            ->__set('two', Properties::create()
                ->__set('three', new Type('number'))
            );

        $schema->properties = $properties;
        $rawData = array(
            'stringValue' => 'abc',
            'one' => array(
                'two' => array(
                    'three' => 3
                ),
            ),
        );
        $data = $schema->import($rawData);

        $this->assertSame('abc', $data->stringValue);
        $this->assertSame(3, $data->one->two->three);


        $rawDataTwo = $schema->export($data);
        $this->assertSame($rawData, $rawDataTwo);
    }

}