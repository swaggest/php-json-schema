<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Spec;


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
                if ($path === '.' || $path === '..') {
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
            'format.json validation of uuid strings: not valid uuid' => 1,
            'format.json validation of JSON-pointer URI fragment strings: not a valid JSON-pointer as uri fragment (% not URL-encoded)' => 1,
            'format.json validation of URL strings: an invalid URL string' => 1,
            '62_resolution_scope_change.json change resolution scope - change filename (#62): string is valid' => 1,
        );

        // debug particular test
        //return '1_ids_in_refs.json IDs in refs with root id: valid' !== $name;

        return isset($skip[$name]);
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

    /**
     * @dataProvider specExtrasProvider
     * @param $schemaData
     * @param $data
     * @param $isValid
     * @param $name
     * @throws \Exception
     */
    public function testSpecExtras($schemaData, $data, $isValid, $name)
    {
        if ($this->skipTest($name)) {
            $this->markTestSkipped();
            return;
        }
        $this->runSpecTest($schemaData, $data, $isValid, $name, static::SCHEMA_VERSION);
    }

    public function specSchemasProvider()
    {
        $path = __DIR__ . '/../../../../spec/ajv/spec/tests/schemas';
        return $this->provider($path);
    }

    /**
     * @dataProvider specSchemasProvider
     * @param $schemaData
     * @param $data
     * @param $isValid
     * @param $name
     * @throws \Exception
     */
    public function testSpecSchemas($schemaData, $data, $isValid, $name)
    {
        if ($this->skipTest($name)) {
            $this->markTestSkipped();
            return;
        }
        $this->runSpecTest($schemaData, $data, $isValid, $name, static::SCHEMA_VERSION);
    }

    public function specDataProvider()
    {
        $path = __DIR__ . '/../../../../spec/ajv/spec/extras/$data';
        return $this->provider($path);
    }

    /**
     * @dataProvider specDataProvider
     * @param $schemaData
     * @param $data
     * @param $isValid
     * @param $name
     * @throws \Exception
     */
    /*
    public function testSpecData($schemaData, $data, $isValid, $name)
    {
        $this->markTestSkipped();
        if ($this->skipTest($name)) {
            $this->markTestSkipped();
            return;
        }
        $this->runSpecTest($schemaData, $data, $isValid, $name, static::SCHEMA_VERSION);
    }
    */

}