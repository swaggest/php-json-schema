<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Spec;


class Draft4Test extends SchemaTestSuite
{
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

        // Uncomment to debug a specific test case.
//        return 'enum.json enum with 0 does not match false: false is invalid [0]' !== $name;
        return false;
    }

    public function specOptionalProvider()
    {
        $path = __DIR__ . '/../../../../spec/JSON-Schema-Test-Suite/tests/draft4/optional';
        return $this->provider($path);
    }

    public function specProvider()
    {
        $path = __DIR__ . '/../../../../spec/JSON-Schema-Test-Suite/tests/draft4';
        return $this->provider($path);
    }
}