<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Spec;


use Swaggest\JsonSchema\Schema;

class Draft6Test extends Draft4Test
{
    const SCHEMA_VERSION = Schema::VERSION_DRAFT_06;

    public function specProvider()
    {
        $path = __DIR__ . '/../../../../spec/JSON-Schema-Test-Suite/tests/draft6';
        return $this->provider($path);
    }

    public function specOptionalProvider()
    {
        $path = __DIR__ . '/../../../../spec/JSON-Schema-Test-Suite/tests/draft6/optional';
        return $this->provider($path);
    }

}