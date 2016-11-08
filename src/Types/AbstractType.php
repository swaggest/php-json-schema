<?php

namespace Yaoi\Schema\Types;

use Yaoi\Schema\Base;
use Yaoi\Schema\OldConstraint;
use Yaoi\Schema\OldSchema;
use Yaoi\Schema\Transformer;
use Yaoi\Schema\OldConstraint\Type;

abstract class AbstractType extends Base implements Transformer
{
    const TYPE = null;

    /**
     * @var OldSchema
     */
    protected $ownerSchema;

    public function __construct(OldSchema $ownerSchema)
    {
        $this->ownerSchema = $ownerSchema;
    }

    /**
     * @param Constraint ...$constraint
     * @return OldSchema
     */
    public static function makeSchema($constraint = null)
    {
        $schema = new OldSchema(array(Type::getSchemaKey() => static::TYPE));
        if ($constraint) {
            $constraints = func_get_args();
            foreach ($constraints as $constraintItem) {
                $schema->setConstraint($constraintItem);
            }
        }
        return $schema;
    }

    public static function getFromSchema(OldSchema $schema)
    {
        $type = Type::getFromSchema($schema);
        if (null === $type) {
            return null;
        }
        return $type->getHandlerByType(static::TYPE);
    }


}