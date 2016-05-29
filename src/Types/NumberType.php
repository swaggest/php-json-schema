<?php

namespace Yaoi\Schema\Types;

use Yaoi\Schema\Exception;
use Yaoi\Schema\TypeConstraint;

/**
 * @method float import($data)
 * @method float export($structure)
 */
class NumberType extends RawStructure implements TypeConstraint
{
    /**
     * @param float $data
     * @return float
     * @throws Exception
     */
    protected function innerImport($data)
    {
        // TODO consider strict is_float or is_int check here
        $result = filter_var($data, FILTER_VALIDATE_FLOAT);
        if ($result === false) {
            throw new Exception('Invalid number', Exception::INVALID_VALUE);
        }
        return $result;
    }

    /**
     * @param float $structure
     * @return float
     */
    protected function innerExport($structure)
    {
        return $this->innerImport($structure);
    }

    /**
     * @inheritDoc
     */
    public function getPhpDocType()
    {
        return 'float';
    }

}