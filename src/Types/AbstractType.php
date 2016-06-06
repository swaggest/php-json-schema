<?php

namespace Yaoi\Schema\Types;

use Yaoi\Schema\AbstractConstraint;
use Yaoi\Schema\Schema;
use Yaoi\Schema\Type;
use Yaoi\Schema\TypeConstraint;

class AbstractType extends AbstractConstraint implements TypeConstraint
{
    public function __construct(Schema $ownerSchema = null)
    {
        $this->ownerSchema = $ownerSchema;
    }

    public static function makeSchema()
    {
        return new Schema(array(Type::KEY => StringType::TYPE_STRING));
    }


}