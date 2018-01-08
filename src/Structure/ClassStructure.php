<?php

namespace Swaggest\JsonSchema\Structure;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Context;
use Swaggest\JsonSchema\NameMirror;
use Swaggest\JsonSchema\Schema;

abstract class ClassStructure extends ObjectItem implements ClassStructureContract
{
    use ClassStructureTrait;
}