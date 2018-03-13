<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Path;


use Swaggest\JsonSchema\Path\PointerUtil;

class PointerUtilTest extends \PHPUnit_Framework_TestCase
{
    public function testGetDataPointer()
    {
        $path = '#->properties:responses->additionalProperties:envvar->properties:schema';
        $dataPointer = PointerUtil::getDataPointer($path);
        $this->assertSame('#/responses/envvar/schema', $dataPointer);
    }

}