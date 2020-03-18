<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Spec;


use Swaggest\JsonSchema\Schema;

class Draft7Test extends Draft4Test
{
    const SCHEMA_VERSION = Schema::VERSION_DRAFT_07;

    protected function skipTest($name)
    {
        // Emulating ecmascript regex in PHP seems not feasible.
        if (substr($name, 0, strlen('ecmascript-regex.json')) === 'ecmascript-regex.json'
            && false !== strpos($name, 'Python')) {
            return true;
        }

        if ($name === 'ecmascript-regex.json ECMA 262 \d matches ascii digits only: NKO DIGIT ZERO (as \u escape) does not match [2]' ||
            $name === 'ecmascript-regex.json ECMA 262 \D matches everything but ascii digits: NKO DIGIT ZERO (as \u escape) matches [2]') {
            return true;
        }

        //$pass = 'refRemote.json root ref in remote ref';
        //return substr($name, 0, strlen($pass)) !== $pass;

        static $skip = array(
//            'iri.json validation of IRIs: a valid IRI based on IPv6 [4]' =>
//                'invalid case, see https://github.com/json-schema-org/JSON-Schema-Test-Suite/pull/213',
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