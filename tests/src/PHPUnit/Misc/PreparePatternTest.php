<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Misc;

use Swaggest\JsonSchema\Helper;

class PreparePatternTest extends \PHPUnit_Framework_TestCase
{
    public function testPreparePatternForEmail()
    {
        $pattern = Helper::toPregPattern('^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$');
        $this->assertEquals(1, preg_match($pattern, 'name@host.com'));
        $this->assertEquals(0, preg_match($pattern, "malformed-email"));
    }
}