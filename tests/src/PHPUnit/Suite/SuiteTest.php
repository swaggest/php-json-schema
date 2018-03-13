<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Suite;


use Swaggest\JsonSchema\Tests\PHPUnit\Spec\SchemaTestSuite;

class SuiteTest extends SchemaTestSuite
{
    protected function skipTest($name)
    {
        return false;
    }

    public function specProvider()
    {
        return $this->provider(__DIR__ . '/../../../resources/suite');
    }

    public function specOptionalProvider()
    {
        return array();
    }
}