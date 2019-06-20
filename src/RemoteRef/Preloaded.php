<?php

namespace Swaggest\JsonSchema\RemoteRef;

use Swaggest\JsonSchema\Context;
use Swaggest\JsonSchema\RefResolver;
use Swaggest\JsonSchema\RemoteRefProvider;
use Swaggest\JsonSchema\Schema;

class Preloaded implements RemoteRefProvider
{
    private $storage;

    private $schemaFiles;

    public function __construct()
    {
        $this->schemaFiles = array(
            'http://json-schema.org/draft-04/schema' => __DIR__ . '/../../spec/json-schema.json',
            'http://json-schema.org/draft-06/schema' => __DIR__ . '/../../spec/json-schema-draft6.json',
            'http://json-schema.org/draft-07/schema' => __DIR__ . '/../../spec/json-schema-draft7.json',
        );
    }

    public function getSchemaData($url)
    {
        if (isset($this->storage[$url])) {
            return $this->storage[$url];
        } elseif (isset($this->schemaFiles[$url])) {
            if ($data = file_get_contents($this->schemaFiles[$url])) {
                $this->storage[$url] = json_decode($data);
            } else {
                return false;
            }
            return $this->storage[$url];
        }
        return false;
    }

    /**
     * @param RefResolver $refResolver
     * @param Context|null $options
     * @throws \Swaggest\JsonSchema\Exception
     */
    public function populateSchemas(RefResolver $refResolver, Context $options = null)
    {
        if ($options === null) {
            $options = new Context();
            $options->refResolver = $refResolver;
            $options->version = Schema::VERSION_AUTO;
        }

        $prev = $refResolver->getResolutionScope();
        foreach ($this->storage as $url => $schemaData) {
            $refResolver->setupResolutionScope($url, $schemaData);
            $refResolver->setResolutionScope($url);
            $refResolver->preProcessReferences($schemaData, $options);
        }
        $refResolver->setResolutionScope($prev);
    }

    public function setSchemaData($url, $schemaData)
    {
        $this->storage[rtrim($url, '#')] = $schemaData;
        return $this;
    }

    public function setSchemaFile($url, $path)
    {
        $this->schemaFiles[rtrim($url, '#')] = $path;
        return $this;
    }


}