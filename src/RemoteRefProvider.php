<?php

namespace Swaggest\JsonSchema;


interface RemoteRefProvider
{
    /**
     * @param string $url
     * @return \stdClass|false json_decode of $url resource content
     */
    public function getSchemaData($url);
}