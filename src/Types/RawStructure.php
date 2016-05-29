<?php

namespace Yaoi\Schema\Types;

use Yaoi\Schema\AbstractStructure;

class RawStructure extends AbstractStructure
{
    protected function innerImport($data)
    {
        return $data;
    }

    /**
     * @param mixed $structure
     * @return mixed
     */
    protected function innerExport($structure)
    {
        return $structure;
    }
}