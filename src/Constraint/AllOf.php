<?php

namespace Yaoi\Schema\Constraint;

use Yaoi\Schema\AbstractConstraint;
use Yaoi\Schema\Constraint;
use Yaoi\Schema\Exception;
use Yaoi\Schema\Schema;
use Yaoi\Schema\Transformer;

class AllOf extends AbstractConstraint implements Transformer, Constraint
{
    public static function getSchemaKey()
    {
        return 'allOf';
    }

    /** @var Schema[] */
    private $composition;

    public function __construct($schemaValue, Schema $ownerSchema = null)
    {
        if (!is_array($schemaValue)) {
            throw new Exception('Array expected', Exception::INVALID_VALUE);
        }
        $this->composition = array();
        foreach ($schemaValue as $item) {
            $this->composition[] = new Schema($item, $ownerSchema);
        }
    }


    public function import($data)
    {
        foreach ($this->composition as $item) {

        }
        // @todo implement
        return $data;
    }

    public function export($entity)
    {
        // TODO: Implement export() method.
        return $entity;
    }


}