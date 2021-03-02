<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Spec;


use Swaggest\JsonSchema\RefResolver;
use Swaggest\JsonSchema\RemoteRef\Preloaded;
use Swaggest\JsonSchema\Schema;

class AjvTest extends SchemaTestSuite
{
    const SCHEMA_VERSION = Schema::VERSION_AUTO;

    public static function getProvider()
    {
        static $refProvider = null;

        if (null === $refProvider) {
            $refProvider = new Preloaded();
            $refProvider
                ->setSchemaData(
                    'http://localhost:1234/integer.json',
                    json_decode(file_get_contents(__DIR__
                        . '/../../../../spec/JSON-Schema-Test-Suite/remotes/integer.json')))
                ->setSchemaData(
                    'http://localhost:1234/subSchemas.json',
                    json_decode(file_get_contents(__DIR__
                        . '/../../../../spec/JSON-Schema-Test-Suite/remotes/subSchemas.json')))
                ->setSchemaData(
                    'http://localhost:1234/name.json',
                    json_decode(file_get_contents(__DIR__
                        . '/../../../../spec/JSON-Schema-Test-Suite/remotes/name.json')))
                ->setSchemaData(
                    'http://localhost:1234/folder/folderInteger.json',
                    json_decode(file_get_contents(__DIR__
                        . '/../../../../spec/JSON-Schema-Test-Suite/remotes/folder/folderInteger.json')));


            $refProvider->setSchemaData('http://swagger.io/v2/schema.json', json_decode(file_get_contents(__DIR__
                . '/../../../../spec/swagger-schema.json')));

            $dir = __DIR__ . '/../../../../spec/ajv/spec/remotes/';
            foreach (new \DirectoryIterator($dir) as $path) {
                if ($path->getFilename() === '.' || $path->getFilename() === '..') {
                    continue;
                }
                $refProvider->setSchemaData('http://localhost:1234/'
                    . $path, json_decode(file_get_contents($dir . $path)));
            }
        }

        return $refProvider;
    }


    protected function skipTest($name)
    {
        static $skip = array(
            //'format.json validation of uuid strings: not valid uuid [2]' => 1,
            //'format.json validation of JSON-pointer URI fragment strings: not a valid JSON-pointer as uri fragment (% not URL-encoded) [1]' => 1,
            //'62_resolution_scope_change.json change resolution scope - change filename (#62): string is valid [0]' => 1,
        );

        // debug single test case
        //return '13_root_ref_in_ref_in_remote_ref.json root ref in remote ref (#13): string is valid [0]' !== $name;

        return isset($skip[$name]);
    }

    /**
     * @param $schemaData
     * @param $data
     * @param $isValid
     * @param $name
     * @param $version
     * @throws \Exception
     */
    protected function runSpecTest($schemaData, $data, $isValid, $name, $version)
    {
        if (isset($schemaData->format) && in_array($schemaData->format, array('url', 'uuid', 'json-pointer-uri-fragment'))) {
            $this->markTestSkipped($schemaData->format . ' format is not supported');
            return;
        }
        parent::runSpecTest($schemaData, $data, $isValid, $name, $version);
    }


    public function specOptionalProvider()
    {
        $path = __DIR__ . '/../../../../spec/ajv/spec/tests/issues';
        return $this->provider($path);
    }

    public function specProvider()
    {
        $path = __DIR__ . '/../../../../spec/ajv/spec/tests/rules';
        return $this->provider($path);
    }


    public function specExtrasProvider()
    {
        $path = __DIR__ . '/../../../../spec/ajv/spec/extras';
        return $this->provider($path);
    }

    protected function makeOptions($version)
    {
        $options = parent::makeOptions($version);
        $options->refResolver = new RefResolver();
        if ($options->remoteRefProvider instanceof Preloaded) {
            $options->remoteRefProvider->populateSchemas($options->refResolver, $options);
        }
        return $options;
    }

    /**
     * @dataProvider specExtrasProvider
     * @param $schema
     * @param $data
     * @param $isValid
     * @param $name
     * @throws \Exception
     */
    public function testSpecExtras($schema, $data, $isValid, $name)
    {
        if ($this->skipTest($name)) {
            $this->markTestSkipped();
            return;
        }
        $this->runSpecTest($schema, $data, $isValid, $name, static::SCHEMA_VERSION);
    }

    public function specSchemasProvider()
    {
        $path = __DIR__ . '/../../../../spec/ajv/spec/tests/schemas';
        return $this->provider($path);
    }

    /**
     * @dataProvider specSchemasProvider
     * @param $schema
     * @param $data
     * @param $isValid
     * @param $name
     * @throws \Exception
     */
    public function testSpecSchemas($schema, $data, $isValid, $name)
    {
        if ($this->skipTest($name)) {
            $this->markTestSkipped();
            return;
        }
        $this->runSpecTest($schema, $data, $isValid, $name, static::SCHEMA_VERSION);
    }

    public function specDataProvider()
    {
        $path = __DIR__ . '/../../../../spec/ajv/spec/extras/$data';
        return $this->provider($path);
    }

    /**
     * @dataProvider specDataProvider
     * @param $schema
     * @param $data
     * @param $isValid
     * @param $name
     * @throws \Exception
     */
    /*
    public function testSpecData($schema, $data, $isValid, $name)
    {
        $this->markTestSkipped();
        if ($this->skipTest($name)) {
            $this->markTestSkipped();
            return;
        }
        $this->runSpecTest($schema, $data, $isValid, $name, static::SCHEMA_VERSION);
    }
    */

}