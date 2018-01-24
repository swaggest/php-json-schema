<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Ref;


use Swaggest\JsonSchema\Exception;
use Swaggest\JsonSchema\RefResolver;
use Swaggest\JsonSchema\RemoteRef\Preloaded;

class InnerScopeTest extends \PHPUnit_Framework_TestCase
{
    public function testInnerDef()
    {
        $p = new Preloaded();
        $p->setSchemaData('http://localhost:1234/scope_change.json',
            json_decode(file_get_contents(__DIR__ . '/../../../../spec/ajv/spec/remotes/scope_change.json')));
        $r = new RefResolver();
        $r->setRemoteRefProvider($p);
        try {
            $p->populateSchemas($r);
            $ref = $r->resolveReference('http://localhost:1234/scope_foo.json#/definitions/bar');
            $this->assertEquals((object)array('type' => 'string'), $ref->getData());
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

}