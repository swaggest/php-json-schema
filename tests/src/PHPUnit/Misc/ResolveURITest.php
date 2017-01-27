<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Misc;


use Swaggest\JsonSchema\Helper;

class ResolveURITest extends \PHPUnit_Framework_TestCase
{
    public function testResolve()
    {
        $root = 'http://x.y.z/rootschema.json#';
        $this->assertSame($root, Helper::resolveURI($root, ''));
        $this->assertSame('http://x.y.z/rootschema.json#foo', Helper::resolveURI($root, "#foo"));
        $deeper = Helper::resolveURI($root, "otherschema.json");
        $this->assertSame('http://x.y.z/otherschema.json#', $deeper);
        $this->assertSame('http://x.y.z/otherschema.json#bar', Helper::resolveURI($deeper, "#bar"));
        $this->assertSame('http://x.y.z/t/inner.json#a', Helper::resolveURI($deeper, "t/inner.json#a"));
        $this->assertSame("some://where.else/completely#", Helper::resolveURI($root, "some://where.else/completely#"));
    }

}