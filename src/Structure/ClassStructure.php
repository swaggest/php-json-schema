<?php

namespace Swaggest\JsonSchema\Structure;

abstract class ClassStructure implements ClassStructureContract, WithResolvedValue
{
    use ClassStructureTrait;
}