<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Spec;

use Swaggest\JsonSchema\Context;
use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\RemoteRef\Preloaded;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\SchemaDraft6;

class SpecTest extends \PHPUnit_Framework_TestCase
{
    const DRAFT_04 = 4;
    const DRAFT_06 = 6;

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
        }

        return $refProvider;
    }

    /**
     * @dataProvider spec4Provider
     * @param $schemaData
     * @param $data
     * @param $isValid
     * @throws \Exception
     */
    public function testSpecDraft4($schemaData, $data, $isValid)
    {
        $this->runSpecTest($schemaData, $data, $isValid, self::DRAFT_04);
    }

    /**
     * @dataProvider spec6Provider
     * @param $schemaData
     * @param $data
     * @param $isValid
     * @throws \Exception
     */
    public function testSpecDraft6($schemaData, $data, $isValid)
    {
        $this->runSpecTest($schemaData, $data, $isValid, self::DRAFT_06);
    }

    /**
     * @param $schemaData
     * @param $data
     * @param $isValid
     * @param $version
     * @throws \Exception
     */
    private function runSpecTest($schemaData, $data, $isValid, $version)
    {
        $refProvider = self::getProvider();

        $actualValid = true;
        $error = '';
        try {
            $options = new Context();
            $options->setRemoteRefProvider($refProvider);

            $schema = Schema::import($schemaData, $options);

            $res = $schema->in($data);


            $exported = $schema->out($res);
            $this->assertEquals($data, $exported);
        } catch (InvalidValue $exception) {
            $actualValid = false;
            $error = $exception->getMessage();
        }

        $this->assertSame($isValid, $actualValid, "Schema:\n" . json_encode($schemaData, JSON_PRETTY_PRINT)
            . "\nData:\n" . json_encode($data, JSON_PRETTY_PRINT)
            . "\nError: " . $error . "\n");

    }


    /**
     * @dataProvider spec4Provider
     * @param $schemaData
     * @param $data
     * @param $isValid
     */
    public function testSpecDraft4SkipValidation($schemaData, $data, $isValid)
    {
        $this->runSpecTestSkipValidation($schemaData, $data, $isValid);
    }

    /**
     * @dataProvider spec6Provider
     * @param $schemaData
     * @param $data
     * @param $isValid
     */
    public function testSpecDraft6SkipValidation($schemaData, $data, $isValid)
    {
        $this->runSpecTestSkipValidation($schemaData, $data, $isValid);
    }


    private function runSpecTestSkipValidation($schemaData, $data, $isValid)
    {
        $refProvider = self::getProvider();

        $actualValid = true;
        $error = '';
        try {
            $options = new Context();
            $options->setRemoteRefProvider($refProvider);
            $schema = Schema::import($schemaData, $options);
            $context = new Context();
            $context->skipValidation = true;
            $res = $schema->in($data, $context);

            $context = new Context();
            $context->skipValidation = true;
            $exported = $schema->out($res, $context);
            $this->assertEquals($data, $exported);
        } catch (InvalidValue $exception) {
            $actualValid = false;
            $error = $exception->getMessage();
            throw $exception;
        }


        $this->assertTrue($actualValid, "Schema:\n" . json_encode($schemaData, JSON_PRETTY_PRINT)
            . "\nData:\n" . json_encode($data, JSON_PRETTY_PRINT)
            . "\nError: " . $error . "\n");
    }


    public function spec4Provider()
    {
        $path = __DIR__ . '/../../../../spec/JSON-Schema-Test-Suite/tests/draft4';
        return $this->provider($path);
    }

    public function spec6Provider()
    {
        $path = __DIR__ . '/../../../../spec/JSON-Schema-Test-Suite/tests/draft6';
        return $this->provider($path);
    }

    public function provider($path)
    {
        if (!file_exists($path)) {
            //$this->markTestSkipped('No spec tests found, please run `git submodule bla-bla`');
        }

        $testCases = array();

        if ($handle = opendir($path)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    if ('.json' !== substr($entry, -5)) {
                        continue;
                    }

                    //if ($entry !== 'refRemote.json') {
                    //continue;
                    //}

                    //echo "$entry\n";
                    /** @var _SpecTest[] $tests */
                    $tests = json_decode(file_get_contents($path . '/' . $entry));
                    foreach ($tests as $test) {
                        foreach ($test->tests as $case) {
                            /*if ($case->description !== 'changed scope ref invalid') {
                                continue;
                            }
                            */

                            $testCases[$entry . ' ' . $test->description . ': ' . $case->description] = array(
                                'schema' => $test->schema,
                                'data' => $case->data,
                                'isValid' => $case->valid,
                            );
                        }
                    }
                }
            }
            closedir($handle);
        }

        //print_r($testCases);

        return $testCases;
    }


}

/**
 * @property $description
 * @property $schema
 * @property _SpecTestCase[] $tests
 */
class _SpecTest
{
}

/**
 * @property $description
 * @property $data
 * @property bool $valid
 */
class _SpecTestCase
{
}