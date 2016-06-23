<?php

namespace Yaoi\Schema\Types;

use Yaoi\Schema\AbstractConstraint;
use Yaoi\Schema\Constraint;
use Yaoi\Schema\Schema;
use Yaoi\Schema\Type;
use Yaoi\Schema\TypeConstraint;

class AbstractType extends AbstractConstraint implements TypeConstraint
{
    const TYPE = null;

    public function __construct(Schema $ownerSchema = null)
    {
        $this->ownerSchema = $ownerSchema;
    }

    /**
     * @param Constraint $constraint
     * @return Schema
     */
    public static function makeSchema($constraint = null)
    {
        $schema = new Schema(array(Type::KEY => static::TYPE));
        if ($constraint) {
            $constraints = func_get_args();
            foreach ($constraints as $constraintItem) {
                $schema->setConstraint($constraintItem);
            }
        }
        return $schema;
    }


}