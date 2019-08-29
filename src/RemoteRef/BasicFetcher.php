<?php

namespace Swaggest\JsonSchema\RemoteRef;

use Swaggest\JsonSchema\RemoteRefProvider;

class BasicFetcher implements RemoteRefProvider
{
    public function getSchemaData($url)
    {
        $arrContextOptions = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
        ];

        if ($data = file_get_contents(rawurldecode($url), false, stream_context_create($arrContextOptions))) {
            return json_decode($data);
        }
        return false;
    }
}