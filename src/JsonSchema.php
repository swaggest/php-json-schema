<?php
/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as the splendid AI will throw them away with no mercy.
 */

namespace Swaggest\JsonSchema;

use Swaggest\JsonSchema\Constraint\Format;
use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Constraint\Type;
use Swaggest\JsonSchema\Schema as JsonBasicSchema;
use Swaggest\JsonSchema\Structure\ClassStructure;


/**
 * Core schema meta-schema
 *
 * // draft6
 * @property mixed $const
 *
 * @property mixed $default
 */
class JsonSchema extends ClassStructure
{
    const _ARRAY = 'array';

    const BOOLEAN = 'boolean';

    const INTEGER = 'integer';

    const NULL = 'null';

    const NUMBER = 'number';

    const OBJECT = 'object';

    const STRING = 'string';

    /** @var string */
    public $id;

    /** @var string */
    public $schema;

    /** @var string */
    public $title;

    /** @var string */
    public $description;

    /** @var float|null */
    public $multipleOf;

    /** @var float|null */
    public $maximum;

    /** @var bool|float|null */
    public $exclusiveMaximum;

    /** @var float|null */
    public $minimum;

    /** @var bool|float */
    public $exclusiveMinimum;

    /** @var int|null */
    public $maxLength;

    /** @var int|null */
    public $minLength;

    /** @var string|null */
    public $pattern;

    /** @var bool|JsonSchema */
    public $additionalItems;

    /** @var JsonSchema|JsonSchema[]|array */
    public $items;

    /** @var int|null */
    public $maxItems;

    /** @var int|null */
    public $minItems;

    /** @var bool|null */
    public $uniqueItems;

    /** @var int */
    public $maxProperties;

    /** @var int */
    public $minProperties;

    /** @var string[]|array */
    public $required;

    /** @var bool|JsonSchema */
    public $additionalProperties;

    /** @var JsonSchema[] */
    public $definitions;

    /** @var JsonSchema[] */
    public $properties;

    /** @var JsonSchema[] */
    public $patternProperties;

    /** @var JsonSchema[]|string[][]|array[] */
    public $dependencies;

    /** @var array */
    public $enum;

    /** @var array|string */
    public $type;

    /** @var string|null */
    public $format;

    /** @var string */
    public $ref;

    /** @var JsonSchema[]|array */
    public $allOf;

    /** @var JsonSchema[]|array */
    public $anyOf;

    /** @var JsonSchema[]|array */
    public $oneOf;

    /** @var JsonSchema Core schema meta-schema */
    public $not;


    // draft6
    /** @var JsonSchema */
    public $contains;

    /** @var JsonSchema */
    public $propertyNames;

    // draft7
    /** @var JsonSchema */
    public $if;

    /** @var JsonSchema */
    public $then;

    /** @var JsonSchema */
    public $else;

    /** @var string */
    public $contentMediaType;

    /** @var string */
    public $contentEncoding;

    /**
     * @param Properties|static $properties
     * @param JsonBasicSchema $ownerSchema
     */
    public static function setUpProperties($properties, JsonBasicSchema $ownerSchema)
    {
        $properties->id = JsonBasicSchema::string();
        $properties->id->format = 'uri';
        $properties->schema = JsonBasicSchema::string();
        $properties->schema->format = 'uri';
        $ownerSchema->addPropertyMapping('$schema', self::names()->schema);
        $properties->title = JsonBasicSchema::string();
        $properties->description = JsonBasicSchema::string();
        $properties->default = new JsonBasicSchema();
        $properties->multipleOf = JsonBasicSchema::number();
        $properties->multipleOf->minimum = 0;
        $properties->multipleOf->exclusiveMinimum = true;
        $properties->maximum = JsonBasicSchema::number();
        $properties->exclusiveMaximum = new JsonBasicSchema(); // draft6
        $properties->exclusiveMaximum->type = array(Type::BOOLEAN, Type::NUMBER); // draft6
        //$properties->exclusiveMaximum = JsonBasicSchema::boolean(); // draft6
        //$properties->exclusiveMaximum->default = false; // draft6
        $properties->minimum = JsonBasicSchema::number();
        $properties->exclusiveMinimum = new JsonBasicSchema(); // draft6
        $properties->exclusiveMinimum->type = array(Type::BOOLEAN, Type::NUMBER); // draft6
        //$properties->exclusiveMinimum = JsonBasicSchema::boolean(); // draft6
        //$properties->exclusiveMinimum->default = false; // draft6
        $properties->maxLength = JsonBasicSchema::integer();
        $properties->maxLength->minimum = 0;
        $properties->minLength = new JsonBasicSchema();
        $properties->minLength->allOf[0] = JsonBasicSchema::integer();
        $properties->minLength->allOf[0]->minimum = 0;
        $properties->minLength->allOf[1] = new JsonBasicSchema();
        $properties->minLength->allOf[1]->default = 0;
        $properties->pattern = JsonBasicSchema::string();
        $properties->pattern->format = 'regex';
        $properties->additionalItems = new JsonBasicSchema();
        $properties->additionalItems->anyOf[0] = JsonBasicSchema::boolean();
        $properties->additionalItems->anyOf[1] = JsonBasicSchema::schema();
        $properties->additionalItems->default = (object)array();
        $properties->items = new JsonBasicSchema();
        $properties->items->anyOf[0] = JsonBasicSchema::schema();
        $properties->items->anyOf[1] = JsonBasicSchema::arr();
        $properties->items->anyOf[1]->items = JsonBasicSchema::schema();
        $properties->items->anyOf[1]->minItems = 1;
        $properties->items->default = (object)array();
        $properties->maxItems = JsonBasicSchema::integer();
        $properties->maxItems->minimum = 0;
        $properties->minItems = new JsonBasicSchema();
        $properties->minItems->allOf[0] = JsonBasicSchema::integer();
        $properties->minItems->allOf[0]->minimum = 0;
        $properties->minItems->allOf[1] = new JsonBasicSchema();
        $properties->minItems->allOf[1]->default = 0;
        $properties->uniqueItems = JsonBasicSchema::boolean();
        $properties->uniqueItems->default = false;
        $properties->maxProperties = JsonBasicSchema::integer();
        $properties->maxProperties->minimum = 0;
        $properties->minProperties = new JsonBasicSchema();
        $properties->minProperties->allOf[0] = JsonBasicSchema::integer();
        $properties->minProperties->allOf[0]->minimum = 0;
        $properties->minProperties->allOf[1] = new JsonBasicSchema();
        $properties->minProperties->allOf[1]->default = 0;
        $properties->required = JsonBasicSchema::arr();
        $properties->required->items = JsonBasicSchema::string();
        //$properties->required->minItems = 1; // disabled by draft6
        $properties->required->uniqueItems = true;
        $properties->additionalProperties = new JsonBasicSchema();
        $properties->additionalProperties->anyOf[0] = JsonBasicSchema::boolean();
        $properties->additionalProperties->anyOf[1] = JsonBasicSchema::schema();
        $properties->additionalProperties->default = (object)array();
        $properties->definitions = JsonBasicSchema::object();
        $properties->definitions->additionalProperties = JsonBasicSchema::schema();
        $properties->definitions->default = (object)array();
        $properties->properties = JsonBasicSchema::object();
        $properties->properties->additionalProperties = JsonBasicSchema::schema();
        $properties->properties->default = (object)array();
        $properties->patternProperties = JsonBasicSchema::object();
        $properties->patternProperties->additionalProperties = JsonBasicSchema::schema();
        $properties->patternProperties->default = (object)array();
        $properties->dependencies = JsonBasicSchema::object();
        $properties->dependencies->additionalProperties = new JsonBasicSchema();
        $properties->dependencies->additionalProperties->anyOf[0] = JsonBasicSchema::schema();
        $properties->dependencies->additionalProperties->anyOf[1] = JsonBasicSchema::arr();
        $properties->dependencies->additionalProperties->anyOf[1]->items = JsonBasicSchema::string();
        //$properties->dependencies->additionalProperties->anyOf[1]->minItems = 1; // disabled by draft6
        $properties->dependencies->additionalProperties->anyOf[1]->uniqueItems = true;
        $properties->enum = JsonBasicSchema::arr();
        $properties->enum->minItems = 1;
        $properties->enum->uniqueItems = true;
        $properties->type = new JsonBasicSchema();
        $anyOf0 = new JsonBasicSchema();
        $anyOf0->enum = array(
            self::_ARRAY,
            self::BOOLEAN,
            self::INTEGER,
            self::NULL,
            self::NUMBER,
            self::OBJECT,
            self::STRING,
        );
        $properties->type->anyOf[0] = $anyOf0;
        $properties->type->anyOf[1] = JsonBasicSchema::arr();
        $properties->type->anyOf[1]->items = new JsonBasicSchema();
        $properties->type->anyOf[1]->items->enum = array(
            self::_ARRAY,
            self::BOOLEAN,
            self::INTEGER,
            self::NULL,
            self::NUMBER,
            self::OBJECT,
            self::STRING,
        );
        $properties->type->anyOf[1]->minItems = 1;
        $properties->type->anyOf[1]->uniqueItems = true;
        $properties->format = JsonBasicSchema::string();
        $properties->ref = JsonBasicSchema::string();
        $properties->ref->format = Format::URI_REFERENCE;
        $ownerSchema->addPropertyMapping('$ref', self::names()->ref);
        $properties->allOf = JsonBasicSchema::arr();
        $properties->allOf->items = JsonBasicSchema::schema();
        $properties->allOf->minItems = 1;
        $properties->anyOf = JsonBasicSchema::arr();
        $properties->anyOf->items = JsonBasicSchema::schema();
        $properties->anyOf->minItems = 1;
        $properties->oneOf = JsonBasicSchema::arr();
        $properties->oneOf->items = JsonBasicSchema::schema();
        $properties->oneOf->minItems = 1;
        $properties->not = JsonBasicSchema::schema();
        $ownerSchema->type = array(self::OBJECT, self::BOOLEAN);
        $ownerSchema->id = 'http://json-schema.org/draft-04/schema#';
        $ownerSchema->schema = 'http://json-schema.org/draft-04/schema#';
        $ownerSchema->description = 'Core schema meta-schema';
        $ownerSchema->default = (object)array();
        $ownerSchema->setFromRef(false);
        // disabled by draft6
        /*
        $ownerSchema->dependencies = (object)array (
          'exclusiveMaximum' =>
          array (
            0 => 'maximum',
          ),
          'exclusiveMinimum' =>
          array (
            0 => 'minimum',
          ),
        );
        */


        // draft6
        $properties->const = (object)array();
        $properties->contains = JsonBasicSchema::schema();
        $properties->propertyNames = JsonBasicSchema::schema();

        // draft7
        $properties->if = JsonBasicSchema::schema();
        $properties->then = JsonBasicSchema::schema();
        $properties->else = JsonBasicSchema::schema();

        $properties->contentEncoding = JsonBasicSchema::string();
        $properties->contentMediaType = JsonBasicSchema::string();
    }

    /**
     * @param string $id
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param string $schema
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setSchema($schema)
    {
        $this->schema = $schema;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param string $title
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param string $description
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param mixed $default
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setDefault($default)
    {
        $this->default = $default;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param float $multipleOf
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setMultipleOf($multipleOf)
    {
        $this->multipleOf = $multipleOf;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param float $maximum
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setMaximum($maximum)
    {
        $this->maximum = $maximum;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param bool $exclusiveMaximum
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setExclusiveMaximum($exclusiveMaximum)
    {
        $this->exclusiveMaximum = $exclusiveMaximum;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param float $minimum
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setMinimum($minimum)
    {
        $this->minimum = $minimum;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param bool $exclusiveMinimum
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setExclusiveMinimum($exclusiveMinimum)
    {
        $this->exclusiveMinimum = $exclusiveMinimum;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param int $maxLength
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setMaxLength($maxLength)
    {
        $this->maxLength = $maxLength;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param int $minLength
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setMinLength($minLength)
    {
        $this->minLength = $minLength;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param string $pattern
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param bool|JsonSchema $additionalItems
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setAdditionalItems($additionalItems)
    {
        $this->additionalItems = $additionalItems;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param JsonSchema|JsonSchema[]|array $items
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setItems($items)
    {
        $this->items = $items;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param int $maxItems
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setMaxItems($maxItems)
    {
        $this->maxItems = $maxItems;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param int $minItems
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setMinItems($minItems)
    {
        $this->minItems = $minItems;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param bool $uniqueItems
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setUniqueItems($uniqueItems)
    {
        $this->uniqueItems = $uniqueItems;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param int $maxProperties
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setMaxProperties($maxProperties)
    {
        $this->maxProperties = $maxProperties;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param int $minProperties
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setMinProperties($minProperties)
    {
        $this->minProperties = $minProperties;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param string[]|array $required
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setRequired($required)
    {
        $this->required = $required;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param bool|JsonSchema $additionalProperties
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setAdditionalProperties($additionalProperties)
    {
        $this->additionalProperties = $additionalProperties;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param JsonSchema[] $definitions
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setDefinitions($definitions)
    {
        $this->definitions = $definitions;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param JsonSchema[] $properties
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param JsonSchema[] $patternProperties
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setPatternProperties($patternProperties)
    {
        $this->patternProperties = $patternProperties;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param JsonSchema[]|string[][]|array[] $dependencies
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setDependencies($dependencies)
    {
        $this->dependencies = $dependencies;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param array $enum
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setEnum($enum)
    {
        $this->enum = $enum;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param array $type
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param string $format
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param string $ref
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setRef($ref)
    {
        $this->ref = $ref;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param JsonSchema[]|array $allOf
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setAllOf($allOf)
    {
        $this->allOf = $allOf;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param JsonSchema[]|array $anyOf
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setAnyOf($anyOf)
    {
        $this->anyOf = $anyOf;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param JsonSchema[]|array $oneOf
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setOneOf($oneOf)
    {
        $this->oneOf = $oneOf;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param JsonSchema $not Core schema meta-schema
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setNot($not)
    {
        $this->not = $not;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */
}

