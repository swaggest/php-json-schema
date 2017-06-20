<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\SkipValidation;

use Swaggest\JsonSchema\Context;
use Swaggest\JsonSchema\Schema;

class SkipValidationTest extends \PHPUnit_Framework_TestCase
{
    public function testSkipValidation()
    {
        $schema = Schema::integer();
        $schema->minimum = 5;
        $options = new Context();
        $options->skipValidation = true;
        $schema->in(4, $options);
    }


    public function testSkipValidationInObject()
    {
        $schema = Schema::object();
        $schema->setProperty('one', Schema::integer());
        $schema->properties->one->minimum = 5;

        $options = new Context();
        $options->skipValidation = true;

        $res = $schema->in(json_decode('{"one":4}'), $options);
        $this->assertSame(4, $res->one);
    }

}