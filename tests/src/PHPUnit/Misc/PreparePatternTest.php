<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Misc;

use Swaggest\JsonSchema\Helper;
use Swaggest\JsonSchema\InvalidValue;

class PreparePatternTest extends \PHPUnit_Framework_TestCase
{
    public function testFailedToPreparePattern()
    {
        $this->setExpectedException(get_class(new InvalidValue()), 'Failed to prepare preg pattern');
        Helper::toPregPattern('/#+~%');
    }
}