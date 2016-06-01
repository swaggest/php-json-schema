<?php

namespace Yaoi\Schema\Types;

use Yaoi\Schema\AbstractConstraint;
use Yaoi\Schema\Schema;
use Yaoi\Schema\TypeConstraint;

class AbstractType extends AbstractConstraint implements TypeConstraint
{
    public $type;
    /** @var Schema */
    public $rootSchema;
    /** @var Schema */
    protected $parentSchema;

    public function __construct($schemaValue, Schema $rootSchema = null, Schema $parentSchema = null)
    {
        $this->type = $schemaValue;
        $this->rootSchema = $rootSchema;
        $this->parentSchema = $parentSchema;
    }


}