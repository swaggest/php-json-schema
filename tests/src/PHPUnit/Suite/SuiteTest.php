<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Suite;


use Swaggest\JsonSchema\RemoteRef\Preloaded;
use Swaggest\JsonSchema\Tests\PHPUnit\Spec\SchemaTestSuite;

class SuiteTest extends SchemaTestSuite
{
    public static function getProvider()
    {
        static $refProvider = null;

        if (null === $refProvider) {
            $refProvider = parent::getProvider();
            $refProvider
                ->setSchemaData(
                    'http://localhost:1234/subSchemas.json',
                    json_decode(file_get_contents(__DIR__
                        . '/../../../resources/remotes/subSchemas.json')));
        }

        return $refProvider;
    }


    protected function skipTest($name)
    {
        return false;
    }

    public function specProvider()
    {
        return $this->provider(__DIR__ . '/../../../resources/suite');
    }

    public function specOptionalProvider()
    {
        return array();
    }
}