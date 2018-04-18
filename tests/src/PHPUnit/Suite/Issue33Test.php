<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Suite;


use Swaggest\JsonSchema\Context;
use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\RemoteRef\Preloaded;
use Swaggest\JsonSchema\Schema;

class Issue33Test extends \PHPUnit_Framework_TestCase
{
    public function testRef() {
        $schemaJson = <<<'JSON'
{
    "id": "https://some-domain/Entity/Foo/Bar/Baz/1.1#",
    "$schema": "http://json-schema.org/draft-04/schema#",
    "allOf": [
        {
            "$ref": "/Entity/1.1#"
        },
        {
            "type": "object",
            "properties": {
                "appId": {
                    "$ref": "/definition/AppID/1.1#"
                },
                "date": {
                    "$ref": "/definition/Date/1.1#"
                },
                "timestamp": {
                    "$ref": "/definition/Date/UnixTimestamp/1.1#"
                }
            }
        }
    ]
}
JSON;
        $provider = new Preloaded();
        $provider->setSchemaData("https://some-domain/Entity/1.1", json_decode('{}'));
        $provider->setSchemaData("https://some-domain/definition/AppID/1.1", json_decode('{"type":"integer"}'));
        $provider->setSchemaData("https://some-domain/definition/Date/1.1", json_decode('{}'));
        $provider->setSchemaData("https://some-domain/definition/Date/UnixTimestamp/1.1", json_decode('{}'));

        $options = new Context();
        $options->setRemoteRefProvider($provider);
        $schema = Schema::import(json_decode($schemaJson), $options);
        $schema->in(json_decode('{"appId":123}'));
        $this->setExpectedException(get_class(new InvalidValue()));
        $schema->in(json_decode('{"appId":"some-string"}'));
    }

}