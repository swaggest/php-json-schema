<?php

namespace Yaoi\Schema\Types;

use Yaoi\Schema\Exception;
use Yaoi\Schema\TypeConstraint;

/**
 * @method int import($data)
 * @method int export($structure)
 */
class IntegerType extends RawStructure implements TypeConstraint
{
    /**
     * @param int|null $data
     * @return int|null
     * @throws Exception
     */
    protected function innerImport($data)
    {
        // TODO consider strict is_int check here
        $result = filter_var($data, FILTER_VALIDATE_INT);
        if ($result === false) {
            throw new Exception('Invalid integer', Exception::INVALID_VALUE);
        }
        return $result;
    }

    /**
     * @param int|null $structure
     * @return int|null
     */
    protected function innerExport($structure)
    {
        return $this->innerImport($structure);
    }

    /**
     * @inheritDoc
     * @deprecated
     */
    public function getPhpDocType()
    {
        return 'int';
    }

}