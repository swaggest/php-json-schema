<?php

namespace Yaoi\Schema\Types;

use Yaoi\Schema\Exception;
use Yaoi\Schema\TypeConstraint;

/**
 * @method bool import($data)
 * @method bool export($structure)
 */
class BooleanType extends RawStructure implements TypeConstraint
{
    /**
     * @param bool $data
     * @return bool
     * @throws Exception
     */
    protected function innerImport($data)
    {
        if (!is_bool($data)) {
            throw new Exception('Boolean value expected', Exception::INVALID_VALUE);
        }
        return $data;
    }

    /**
     * @param bool $structure
     * @return bool
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
        return 'bool';
    }


}