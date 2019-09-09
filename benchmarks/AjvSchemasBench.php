<?php


use Swaggest\JsonSchema\Context;
use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\RemoteRef\Preloaded;
use Swaggest\JsonSchema\Schema;

class AjvSchemasBench
{
    private static $cases;

    public function provide()
    {
        foreach (self::$cases as $name => $tmp) {
            yield $name => ['name' => $name];
        }
    }

    /**
     * @ParamProviders({"provide"})
     */
    public function benchSpec($params)
    {
        $case = self::$cases[$params['name']];

        $actualValid = true;
        try {
            $options = $this->makeOptions(Schema::VERSION_DRAFT_07);
            $options->schemasCache = self::$schemas;

            $schema = Schema::import($case['schema'], $options);
            // import with defaults applied
            $schema->in($case['data'], $options);

            // default is not required to pass validation, so result might be invalid
            // for back-exporting defaults have to be disabled
            $options->applyDefaults = false;
            $imported = $schema->in($case['data'], $options);
            $schema->out($imported);
        } catch (InvalidValue $exception) {
            $actualValid = false;
        }

        if ($actualValid !== $case['isValid']) {
            throw new Exception('Assertion failed');
        }
    }

    /** @var \SplObjectStorage */
    private static $schemas;

    protected function makeOptions($version)
    {
        $refProvider = static::getProvider();

        $options = new Context();
        $options->setRemoteRefProvider($refProvider);
        $options->version = $version;
        $options->strictBase64Validation = true;

        return $options;
    }

    public static function getProvider()
    {
        static $refProvider = null;

        if (null === $refProvider) {
            $refProvider = new Preloaded();
            $refProvider
                ->setSchemaData(
                    'http://localhost:1234/integer.json',
                    json_decode(file_get_contents(__DIR__
                        . '/../spec/JSON-Schema-Test-Suite/remotes/integer.json')))
                ->setSchemaData(
                    'http://localhost:1234/subSchemas.json',
                    json_decode(file_get_contents(__DIR__
                        . '/../spec/JSON-Schema-Test-Suite/remotes/subSchemas.json')))
                ->setSchemaData(
                    'http://localhost:1234/name.json',
                    json_decode(file_get_contents(__DIR__
                        . '/../spec/JSON-Schema-Test-Suite/remotes/name.json')))
                ->setSchemaData(
                    'http://localhost:1234/folder/folderInteger.json',
                    json_decode(file_get_contents(__DIR__
                        . '/../spec/JSON-Schema-Test-Suite/remotes/folder/folderInteger.json')));
        }

        return $refProvider;
    }

    private static function provider($path)
    {
        $testCases = array();

        if ($handle = opendir($path)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    if ('.json' !== substr($entry, -5)) {
                        continue;
                    }
                    $tests = json_decode(file_get_contents($path . '/' . $entry));

                    foreach ($tests as $test) {
                        foreach ($test->tests as $c => $case) {
                            $name = $entry . ' ' . $test->description . ': ' . $case->description . ' [' . $c . ']';
                            if (!isset($test->schema)) {
                                if (isset($test->schemas)) {
                                    foreach ($test->schemas as $i => $schema) {
                                        $testCases[$name . '_' . $i] = array(
                                            'schema' => $schema,
                                            'data' => $case->data,
                                            'isValid' => $case->valid,
                                            'name' => $name,
                                        );
                                    }
                                }
                                continue;
                            }
                            $testCases[$name] = array(
                                'schema' => $test->schema,
                                'data' => $case->data,
                                'isValid' => $case->valid,
                                'name' => $name,
                            );
                        }
                    }
                }
            }
            closedir($handle);
        }

        return $testCases;
    }

    public static function init()
    {
        self::$cases = self::provider(__DIR__ . '/../spec/ajv/spec/tests/schemas');
        self::$schemas = new \SplObjectStorage();
    }
}

AjvSchemasBench::init();