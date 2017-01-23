<?php

namespace Yaoi\Schema\Tests\PHPUnit\Spec;

use Yaoi\Schema\Exception;
use Yaoi\Schema\Schema;
use Yaoi\Schema\SchemaLoader;

class SpecTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provider
     * @param $schema
     * @param $data
     * @param $isValid
     */
    public function testSpecDraft4($schemaData, $data, $isValid)
    {
        $actualValid = true;
        try {
            $schema = SchemaLoader::create()->readSchema($schemaData);
            $res = $schema->import($data);
            //$res = $schema->export($res);
        } catch (Exception $exception) {
            if ($exception->getCode() === Exception::INVALID_VALUE) {
                $actualValid = false;
            } else {
                throw $exception;
            }
        }

        $this->assertSame($isValid, $actualValid, "Schema:\n" . json_encode($schemaData, JSON_PRETTY_PRINT) . "\nData:\n" . json_encode($data, JSON_PRETTY_PRINT));
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
                    //die('!1');

                    //echo "$entry\n";
                    /** @var _SpecTest[] $tests */
                    $tests = json_decode(file_get_contents($path . '/' . $entry));
                    ///print_r($tests);
                    foreach ($tests as $test) {
                        //$schema = SchemaLoader::create()->readSchema($test->schema);
                        foreach ($test->tests as $case) {
                            $testCases[$test->description . ': ' . $case->description] = array(
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
class _SpecTest{}

/**
 * @property $description
 * @property $data
 * @property bool $valid
 */
class _SpecTestCase{}