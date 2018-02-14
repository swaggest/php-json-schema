<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Spec;


use Swaggest\JsonSchema\Schema;

class Draft7SkipValidationTest extends Draft4SkipValidationTest
{
    const SCHEMA_VERSION = Schema::VERSION_DRAFT_07;

    protected function skipTest($name)
    {
        //$pass = 'refRemote.json root ref in remote ref';
        //return substr($name, 0, strlen($pass)) !== $pass;

        static $skip = array(
            'iri.json validation of IRIs: a valid IRI based on IPv6 [4]' =>
                'invalid case, see https://github.com/json-schema-org/JSON-Schema-Test-Suite/pull/213',
        );
        return isset($skip[$name]) ? $skip[$name] : false;
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
        if (false !== $skip = $this->skipTest($name)) {
            $this->markTestSkipped($skip);
            return;
        }
        $this->runSpecTest($schemaData, $data, $isValid, $name, static::SCHEMA_VERSION);
    }


}