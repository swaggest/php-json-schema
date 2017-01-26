<?php

namespace Yaoi\Schema\RemoteRef;

use Yaoi\Schema\RemoteRefProvider;

class BasicFetcher implements RemoteRefProvider
{
    public function getSchemaData($url)
    {
        return json_decode(file_get_contents($url));
    }
}