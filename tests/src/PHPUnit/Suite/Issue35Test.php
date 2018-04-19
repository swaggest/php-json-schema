<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Suite;


use Swaggest\JsonSchema\Context;
use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\RemoteRef\Preloaded;
use Swaggest\JsonSchema\Schema;

class Issue35Test extends \PHPUnit_Framework_TestCase
{
    public function testOne()
    {
        $options = new Context();
        $provider = new Preloaded();
        $provider->setSchemaData('https://some-domain/Baz/Bar#', (object)['type' => 'integer']);
        $options->setRemoteRefProvider($provider);
        $schemaData = (object)[
            'id' => 'https://some-domain/Foo/Bar#',
            'oneOf' => [
                (object)['$ref' => '/Baz/Bar#']
            ]
        ];
        $schema = Schema::import($schemaData, $options);
        $schema->in(1);
        
        $this->assertSame('{"id":"https://some-domain/Foo/Bar#","oneOf":[{"type":"integer"}]}', json_encode($schema, JSON_UNESCAPED_SLASHES));

        $this->setExpectedException(get_class(new InvalidValue()));
        $schema->in("not integer");
    }

    public function testTwo()
    {
        $options = new Context();
        $provider = new Preloaded();
        $provider->setSchemaData('https://some-domain/Baz/Bar', (object)['type' => 'integer']);
        $options->setRemoteRefProvider($provider);
        $schemaData = (object)[
            'id' => 'https://some-domain/Foo/Bar#',
            'oneOf' => [
                (object)['$ref' => '/Baz/Bar#']
            ]
        ];
        $schema = Schema::import($schemaData, $options);
        $schema->in(1);

        $this->assertSame('{"id":"https://some-domain/Foo/Bar#","oneOf":[{"type":"integer"}]}', json_encode($schema, JSON_UNESCAPED_SLASHES));

        $this->setExpectedException(get_class(new InvalidValue()));
        $schema->in("not integer");
    }

    public function testThree()
    {
        $options = new Context();
        $provider = new Preloaded();
        $provider->setSchemaData('https://some-domain/Baz/Bar', (object)['type' => 'integer']);
        $options->setRemoteRefProvider($provider);
        $schemaData = (object)[
            'id' => 'https://some-domain/Foo/Bar#',
            'oneOf' => [
                (object)['$ref' => '/Baz/Bar']
            ]
        ];
        $schema = Schema::import($schemaData, $options);
        $schema->in(1);

        $this->assertSame('{"id":"https://some-domain/Foo/Bar#","oneOf":[{"type":"integer"}]}', json_encode($schema, JSON_UNESCAPED_SLASHES));

        $this->setExpectedException(get_class(new InvalidValue()));
        $schema->in("not integer");
    }

    public function testFour()
    {
        $options = new Context();
        $provider = new Preloaded();
        $provider->setSchemaData('https://some-domain/Baz/Bar#', (object)['type' => 'integer']);
        $options->setRemoteRefProvider($provider);
        $schemaData = (object)[
            'id' => 'https://some-domain/Foo/Bar#',
            'oneOf' => [
                (object)['$ref' => '/Baz/Bar']
            ]
        ];
        $schema = Schema::import($schemaData, $options);
        $schema->in(1);

        $this->assertSame('{"id":"https://some-domain/Foo/Bar#","oneOf":[{"type":"integer"}]}', json_encode($schema, JSON_UNESCAPED_SLASHES));

        $this->setExpectedException(get_class(new InvalidValue()));
        $schema->in("not integer");
    }

}