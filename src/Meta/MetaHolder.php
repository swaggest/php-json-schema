<?php

namespace Swaggest\JsonSchema\Meta;


interface MetaHolder
{
    public function addMeta(Meta $meta);

    public function getMeta($name);

}