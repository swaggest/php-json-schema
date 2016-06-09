<?php

namespace Yaoi\Schema;

class Exception extends \Exception
{
    const INVALID_VALUE = 1;
    const NOT_IMPLEMENTED = 2;

    protected $structureTrace = array();

    public function pushStructureTrace($prefix)
    {
        array_unshift($this->structureTrace, $prefix);
    }

    public function getStructureTrace()
    {
        return $this->structureTrace;
    }


}