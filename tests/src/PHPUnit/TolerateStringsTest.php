<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit;

use Swaggest\JsonSchema\Context;
use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\Schema;

class TolerateStringsTest extends \PHPUnit_Framework_TestCase
{
    public function testWithTolerateStrings()
    {
        $json_schema = json_decode(
            json_encode(
                [
                    'items' => [
                        ['type' => 'string'],
                        ['type' => 'integer'],
                        ['type' => 'number'],
                        ['type' => 'boolean'],
                        [
                            'type' => 'integer',
                            'enum' => [1, 2, 3]
                        ]
                    ]
                ]
            )
        );

        $data = [
            'text',
            '10',
            '123.45',
            'true',
            '2'
        ];

        $expected_data = [
            'text',
            10,
            123.45,
            true,
            2
        ];

        $options = new Context();
        $options->tolerateStrings = true;

        $schema = Schema::import($json_schema);

        $this->assertSame($expected_data, $schema->in($data, $options));
    }

    public function testWithTolerateStringsBadNumber()
    {
        $json_schema = json_decode(
            json_encode(
                [
                    'items' => [
                        ['type' => 'string'],
                        ['type' => 'integer'],
                        ['type' => 'number'],
                        ['type' => 'boolean']
                    ]
                ]
            )
        );

        $data = [
            'text',
            '10',
            'bad',
            'true'
        ];

        $options = new Context();
        $options->tolerateStrings = true;

        $schema = Schema::import($json_schema);

        $this->setExpectedException(get_class(new InvalidValue()), 'Number expected, "bad" received at #->items:2');
        $schema->in($data, $options);
    }

    public function testWithTolerateStringsBadInteger()
    {
        $json_schema = json_decode(
            json_encode(
                [
                    'items' => [
                        ['type' => 'string'],
                        ['type' => 'integer'],
                        ['type' => 'number'],
                        ['type' => 'boolean']
                    ]
                ]
            )
        );

        $data = [
            'text',
            'bad',
            '123.45',
            'true'
        ];

        $options = new Context();
        $options->tolerateStrings = true;

        $schema = Schema::import($json_schema);

        $this->setExpectedException(get_class(new InvalidValue()), 'Integer expected, "bad" received at #->items:1');
        $schema->in($data, $options);
    }

    public function testWithTolerateStringsBadBoolean()
    {
        $json_schema = json_decode(
            json_encode(
                [
                    'items' => [
                        ['type' => 'string'],
                        ['type' => 'integer'],
                        ['type' => 'number'],
                        ['type' => 'boolean']
                    ]
                ]
            )
        );

        $data = [
            'text',
            '10',
            '123.45',
            'bad'
        ];

        $options = new Context();
        $options->tolerateStrings = true;

        $schema = Schema::import($json_schema);

        $this->setExpectedException(get_class(new InvalidValue()), 'Boolean expected, "bad" received at #->items:3');
        $schema->in($data, $options);
    }

    public function testWithTolerateStringsBadEnum()
    {
        $json_schema = json_decode(
            json_encode(
                [
                    'items' => [
                        ['type' => 'string'],
                        ['type' => 'integer'],
                        ['type' => 'number'],
                        ['type' => 'boolean'],
                        [
                            'type' => 'integer',
                            'enum' => [1, 2, 3]
                        ]
                    ]
                ]
            )
        );

        $data = [
            'text',
            '10',
            '123.45',
            'true',
            '5'
        ];

        $options = new Context();
        $options->tolerateStrings = true;

        $schema = Schema::import($json_schema);

        $this->setExpectedException(get_class(new InvalidValue()), 'Enum failed, enum: [1,2,3] at #->items:4');
        $schema->in($data, $options);
    }
}
