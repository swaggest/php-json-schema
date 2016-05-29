<?php

namespace Yaoi\Schema\Types;

use Yaoi\Schema\Exception;
use Yaoi\Schema\Structure;
use Yaoi\Schema\TypeConstraint;

/**
 * @method array import($data)
 * @method array export($structure)
 */
class ArrayType extends RawStructure implements TypeConstraint
{
    /** @var Structure */
    private $itemStructure; // @todo move to separate items constraint
    public function setItemStructure(Structure $itemStructure) {
        $this->itemStructure = $itemStructure;
        return $this;
    }

    /**
     * @param array $data
     * @return Structure[]|array
     * @throws Exception
     */
    protected function innerImport($data)
    {
        if (!is_array($data)) {
            throw new Exception('Array expected', Exception::INVALID_VALUE);
        }

        if ($this->itemStructure === null) {
            $this->itemStructure = RawStructure::create();
        }

        $result = array();
        foreach ($data as $key => $value) {
            try {
                $structureItem = $this->itemStructure->import($value);
            }
            catch (Exception $exception) {
                throw $this->propagateException($key, $exception);
            }

            $result[$key] = $structureItem;
        }
        return $result;
    }

    /**
     * @param array $structure
     * @return array
     * @throws Exception
     */
    protected function innerExport($structure)
    {
        if (!is_array($structure)) {
            throw new Exception('Array expected', Exception::INVALID_VALUE);
        }

        if ($this->itemStructure === null) {
            $this->itemStructure = RawStructure::create();
        }

        $result = array();
        foreach ($structure as $key => $value) {
            try {
                $result[$key] = $this->itemStructure->export($value);
            }
            catch (Exception $exception) {
                throw $this->propagateException($key, $exception);
            }
        }
        return $result;
    }
}