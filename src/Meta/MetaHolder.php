<?php

namespace Swaggest\JsonSchema\Meta;


interface MetaHolder
{
    public function addMeta($meta, $name = null);

    public function getMeta($name);

}