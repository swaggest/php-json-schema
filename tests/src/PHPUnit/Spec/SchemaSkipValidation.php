<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Spec;

use Swaggest\JsonSchema\Context;
use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\Schema;

abstract class SchemaSkipValidation extends SchemaTestSuite
{
    /**
     * @param $schemaData
     * @param $data
     * @param $isValid
     * @param $name
     * @throws \Exception
     * @throws \Swaggest\JsonSchema\Exception
     */
    protected function runSpecTest($schemaData, $data, $isValid, $name, $version)
    {
        $refProvider = static::getProvider();

        $actualValid = true;
        $error = '';
        try {
            $options = new Context();
            $options->setRemoteRefProvider($refProvider);
            $schema = Schema::import($schemaData, $options);
            $context = new Context();
            $context->skipValidation = true;
            $context->unpackContentMediaType = false;
            $context->applyDefaults = false;
            $res = $schema->in($data, $context);

            $context = new Context();
            $context->skipValidation = true;
            $context->unpackContentMediaType = false;
            $exported = $schema->out($res, $context);
            $this->assertEquals($data, $exported);
        } catch (InvalidValue $exception) {
            $actualValid = false;
            $error = $exception->getMessage();
        }


        $this->assertTrue($actualValid, "Schema:\n" . json_encode($schemaData, JSON_PRETTY_PRINT)
            . "\nData:\n" . json_encode($data, JSON_PRETTY_PRINT)
            . "\nError: " . $error . "\n");
    }


}