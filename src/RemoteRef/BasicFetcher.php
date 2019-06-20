<?php

namespace Swaggest\JsonSchema\RemoteRef;

use Swaggest\JsonSchema\RemoteRefProvider;

class BasicFetcher implements RemoteRefProvider
{
    public function getSchemaData($url)
    {
        if ($data = file_get_contents(rawurldecode($url))) {
            return json_decode($data);
        }
        return false;
    }
}
