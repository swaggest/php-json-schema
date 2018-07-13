<?php

namespace Swaggest\JsonSchema\RemoteRef;

use Swaggest\JsonSchema\RemoteRefProvider;

class BasicFetcher implements RemoteRefProvider
{
    public function getSchemaData($url)
    {
        return json_decode(file_get_contents(rawurldecode($url)));
    }
}
