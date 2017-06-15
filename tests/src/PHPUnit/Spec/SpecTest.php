<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Spec;

use Swaggest\JsonSchema\Context;
use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\RemoteRef\Preloaded;
use Swaggest\JsonSchema\Schema;

class SpecTest extends \PHPUnit_Framework_TestCase
{
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
     * @dataProvider provider
     * @param $schemaData
     * @param $data
     * @param $isValid
     * @throws InvalidValue
     */
    public function testSpecDraft4($schemaData, $data, $isValid)
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


    public function provider()
    {
        $path = __DIR__ . '/../../../../spec/JSON-Schema-Test-Suite/tests/draft4';
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