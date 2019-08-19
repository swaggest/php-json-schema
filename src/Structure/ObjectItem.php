<?php

namespace Swaggest\JsonSchema\Structure;

/**
 * @method getNestedObject($className);
 * @method setNestedProperty($propertyName, $value, Egg $nestedEgg);
 * @method addAdditionalPropertyName($name);
 * @method setDocumentPath($path);
 * @method setFromRef($ref);
 * @method string|null getFromRef();
 * @method string[]|null getFromRefs();
 */
class ObjectItem implements ObjectItemContract, WithResolvedValue
{
    use ObjectItemTrait;
}