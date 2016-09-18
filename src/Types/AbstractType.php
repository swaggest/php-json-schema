<?php

namespace Yaoi\Schema\Types;

use Yaoi\Schema\AbstractConstraint;
use Yaoi\Schema\Base;
use Yaoi\Schema\Constraint;
use Yaoi\Schema\Schema;
use Yaoi\Schema\Transformer;
use Yaoi\Schema\Type;
use Yaoi\Schema\TypeConstraint;

abstract class AbstractType extends Base implements TypeConstraint, Transformer
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

    public static function getFromSchema(Schema $schema)
    {
        $type = Type::getFromSchema($schema);
        if (null === $type) {
            return null;
        }
        return $type->getHandlerByType(static::TYPE);
    }


}