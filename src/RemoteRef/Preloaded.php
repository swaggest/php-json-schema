<?php

namespace Yaoi\Schema\RemoteRef;

use Yaoi\Schema\RemoteRefProvider;

class Preloaded implements RemoteRefProvider
{
    private $storage;

    public function __construct()
    {
        $this->setSchemaData('http://json-schema.org/draft-04/schema',
            json_decode(file_get_contents(__DIR__ . '/../../spec/json-schema.json')));
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