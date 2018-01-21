<?php

namespace Swaggest\JsonSchema\RemoteRef;

use Swaggest\JsonSchema\RemoteRefProvider;

class Preloaded implements RemoteRefProvider
{
    private $storage;

    public function __construct()
    {
        $this->setSchemaData('http://json-schema.org/draft-04/schema',
            json_decode(file_get_contents(__DIR__ . '/../../spec/json-schema.json')));
        $this->setSchemaData('http://json-schema.org/draft-06/schema',
            json_decode(file_get_contents(__DIR__ . '/../../spec/json-schema-draft6.json')));
        $this->setSchemaData('http://json-schema.org/draft-07/schema',
            json_decode(file_get_contents(__DIR__ . '/../../spec/json-schema-draft7.json')));
    }

    public function getSchemaData($url)
    {
        if (isset($this->storage[$url])) {
            return $this->storage[$url];
        }
        return false;
    }

    public function setSchemaData($url, $schemaData)
    {
        $this->storage[$url] = $schemaData;
        return $this;
    }


}