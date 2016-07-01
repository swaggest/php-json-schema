<?php

namespace Yaoi\Schema\Types;

use Yaoi\Schema\AbstractConstraint;
use Yaoi\Schema\Constraint;
use Yaoi\Schema\Schema;
use Yaoi\Schema\Type;
use Yaoi\Schema\TypeConstraint;

abstract class AbstractType implements TypeConstraint
{
    const TYPE = null;

    /**
     * @var Schema
     */
    protected $ownerSchema;

    public function __construct(Schema $ownerSchema)
    {
        $this->ownerSchema = $ownerSchema;
    }

    /**
     * @param Constraint ...$constraint
     * @return Schema
     */
    public static function makeSchema($constraint = null)
    {
        $schema = new Schema(array(Type::getSchemaKey() => static::TYPE));
        if ($constraint) {
            $constraints = func_get_args();
            foreach ($constraints as $constraintItem) {
                $schema->setConstraint($constraintItem);
            }
        }
        return $schema;
    }


}