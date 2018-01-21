<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Spec;


use Swaggest\JsonSchema\Schema;

class Draft7Test extends Draft4Test
{
    const SCHEMA_VERSION = Schema::VERSION_DRAFT_07;

    protected function skipTest($name)
    {
        static $skip = array(
            'iri.json validation of IRIs: a valid IRI based on IPv6' => 1,
        );
        return isset($skip[$name]);
    }

    public function specProvider()
    {
        $path = __DIR__ . '/../../../../spec/JSON-Schema-Test-Suite/tests/draft7';
        return $this->provider($path);
    }

    public function specOptionalProvider()
    {
        $path = __DIR__ . '/../../../../spec/JSON-Schema-Test-Suite/tests/draft7/optional';
        return $this->provider($path);
    }


    public function specFormatProvider()
    {
        $path = __DIR__ . '/../../../../spec/JSON-Schema-Test-Suite/tests/draft7/optional/format';
        return $this->provider($path);
    }

    /**
     * @dataProvider specFormatProvider
     * @param $schemaData
     * @param $data
     * @param $isValid
     * @param $name
     * @throws \Exception
     */
    public function testSpecFormat($schemaData, $data, $isValid, $name)
    {
        if ($this->skipTest($name)) {
            $this->markTestSkipped();
            return;
        }
        $this->runSpecTest($schemaData, $data, $isValid, $name, static::SCHEMA_VERSION);
    }


}