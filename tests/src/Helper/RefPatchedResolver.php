<?php

namespace Swaggest\JsonSchema\Tests\Helper;

use Swaggest\JsonSchema\RemoteRefProvider;

class RefPatchedResolver implements RemoteRefProvider
{
    private $resolver;

    private $patches;

    public function __construct(RemoteRefProvider $resolver) {
        $this->resolver = $resolver;
    }

    public function addPatch($url, $func) {
        $this->patches[$url] = $func;
    }

    public function getSchemaData($url)
    {
        $data = $this->resolver->getSchemaData($url);

        if ($data === false) {
            return $data;
        }

        if (isset($this->patches[$url])) {
            $patch = $this->patches[$url];

            $patch($data);
        }
    }
}