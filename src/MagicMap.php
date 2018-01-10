<?php

namespace Swaggest\JsonSchema;

class MagicMap implements \ArrayAccess, \JsonSerializable, \Iterator
{
    use MagicMapTrait;
}