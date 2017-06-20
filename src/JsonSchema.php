<?php
/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as the splendid AI will throw them away with no mercy.
 */

namespace Swaggest\JsonSchema;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema as JsonBasicSchema;
use Swaggest\JsonSchema\Structure\ClassStructure;


class JsonSchema extends ClassStructure {
	/** @var string */
	public $id;

	/** @var string */
	public $schema;

	/** @var string */
	public $title;

	/** @var string */
	public $description;

	public $default;

	/** @var float */
	public $multipleOf;

	/** @var float */
	public $maximum;

	/** @var bool */
	public $exclusiveMaximum;

	/** @var float */
	public $minimum;

	/** @var bool */
	public $exclusiveMinimum;

	/** @var int */
	public $maxLength;

	/** @var int */
	public $minLength;

	/** @var string */
	public $pattern;

	/** @var bool|JsonSchema */
	public $additionalItems;

	/** @var JsonSchema|JsonSchema[]|array */
	public $items;

	/** @var int */
	public $maxItems;

	/** @var int */
	public $minItems;

	/** @var bool */
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

	/** @var array */
	public $type;

	/** @var string */
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
		$properties->exclusiveMaximum = JsonBasicSchema::boolean();
		$properties->exclusiveMaximum->default = false;
		$properties->minimum = JsonBasicSchema::number();
		$properties->exclusiveMinimum = JsonBasicSchema::boolean();
		$properties->exclusiveMinimum->default = false;
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
		$properties->additionalItems->default = (object)array (
		);
		$properties->items = new JsonBasicSchema();
		$properties->items->anyOf[0] = JsonBasicSchema::schema();
		$properties->items->anyOf[1] = JsonBasicSchema::arr();
		$properties->items->anyOf[1]->items = JsonBasicSchema::schema();
		$properties->items->anyOf[1]->minItems = 1;
		$properties->items->default = (object)array (
		);
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
		$properties->required->minItems = 1;
		$properties->required->uniqueItems = true;
		$properties->additionalProperties = new JsonBasicSchema();
		$properties->additionalProperties->anyOf[0] = JsonBasicSchema::boolean();
		$properties->additionalProperties->anyOf[1] = JsonBasicSchema::schema();
		$properties->additionalProperties->default = (object)array (
		);
		$properties->definitions = JsonBasicSchema::object();
		$properties->definitions->additionalProperties = JsonBasicSchema::schema();
		$properties->definitions->default = (object)array (
		);
		$properties->properties = JsonBasicSchema::object();
		$properties->properties->additionalProperties = JsonBasicSchema::schema();
		$properties->properties->default = (object)array (
		);
		$properties->patternProperties = JsonBasicSchema::object();
		$properties->patternProperties->additionalProperties = JsonBasicSchema::schema();
		$properties->patternProperties->default = (object)array (
		);
		$properties->dependencies = JsonBasicSchema::object();
		$properties->dependencies->additionalProperties = new JsonBasicSchema();
		$properties->dependencies->additionalProperties->anyOf[0] = JsonBasicSchema::schema();
		$properties->dependencies->additionalProperties->anyOf[1] = JsonBasicSchema::arr();
		$properties->dependencies->additionalProperties->anyOf[1]->items = JsonBasicSchema::string();
		$properties->dependencies->additionalProperties->anyOf[1]->minItems = 1;
		$properties->dependencies->additionalProperties->anyOf[1]->uniqueItems = true;
		$properties->enum = JsonBasicSchema::arr();
		$properties->enum->minItems = 1;
		$properties->enum->uniqueItems = true;
		$properties->type = new JsonBasicSchema();
		$properties->type->anyOf[0] = new JsonBasicSchema();
		$properties->type->anyOf[0]->enum = array (
		  0 => 'array',
		  1 => 'boolean',
		  2 => 'integer',
		  3 => 'null',
		  4 => 'number',
		  5 => 'object',
		  6 => 'string',
		);
		$properties->type->anyOf[1] = JsonBasicSchema::arr();
		$properties->type->anyOf[1]->items = new JsonBasicSchema();
		$properties->type->anyOf[1]->items->enum = array (
		  0 => 'array',
		  1 => 'boolean',
		  2 => 'integer',
		  3 => 'null',
		  4 => 'number',
		  5 => 'object',
		  6 => 'string',
		);
		$properties->type->anyOf[1]->minItems = 1;
		$properties->type->anyOf[1]->uniqueItems = true;
		$properties->format = JsonBasicSchema::string();
		$properties->ref = JsonBasicSchema::string();
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
		$ownerSchema->type = 'object';
		$ownerSchema->id = 'http://json-schema.org/draft-04/schema#';
		$ownerSchema->schema = 'http://json-schema.org/draft-04/schema#';
		$ownerSchema->description = 'Core schema meta-schema';
		$ownerSchema->default = (object)array (
		);
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
	}

	/**
	 * @param string $id
	 * @return $this
	 */
	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @param string $schema
	 * @return $this
	 */
	public function setSchema($schema)
	{
		$this->schema = $schema;
		return $this;
	}

	/**
	 * @param string $title
	 * @return $this
	 */
	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}

	/**
	 * @param string $description
	 * @return $this
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * @param $default
	 * @return $this
	 */
	public function setDefault($default)
	{
		$this->default = $default;
		return $this;
	}

	/**
	 * @param float $multipleOf
	 * @return $this
	 */
	public function setMultipleOf($multipleOf)
	{
		$this->multipleOf = $multipleOf;
		return $this;
	}

	/**
	 * @param float $maximum
	 * @return $this
	 */
	public function setMaximum($maximum)
	{
		$this->maximum = $maximum;
		return $this;
	}

	/**
	 * @param bool $exclusiveMaximum
	 * @return $this
	 */
	public function setExclusiveMaximum($exclusiveMaximum)
	{
		$this->exclusiveMaximum = $exclusiveMaximum;
		return $this;
	}

	/**
	 * @param float $minimum
	 * @return $this
	 */
	public function setMinimum($minimum)
	{
		$this->minimum = $minimum;
		return $this;
	}

	/**
	 * @param bool $exclusiveMinimum
	 * @return $this
	 */
	public function setExclusiveMinimum($exclusiveMinimum)
	{
		$this->exclusiveMinimum = $exclusiveMinimum;
		return $this;
	}

	/**
	 * @param int $maxLength
	 * @return $this
	 */
	public function setMaxLength($maxLength)
	{
		$this->maxLength = $maxLength;
		return $this;
	}

	/**
	 * @param int $minLength
	 * @return $this
	 */
	public function setMinLength($minLength)
	{
		$this->minLength = $minLength;
		return $this;
	}

	/**
	 * @param string $pattern
	 * @return $this
	 */
	public function setPattern($pattern)
	{
		$this->pattern = $pattern;
		return $this;
	}

	/**
	 * @param bool|JsonSchema $additionalItems
	 * @return $this
	 */
	public function setAdditionalItems($additionalItems)
	{
		$this->additionalItems = $additionalItems;
		return $this;
	}

	/**
	 * @param JsonSchema|JsonSchema[]|array $items
	 * @return $this
	 */
	public function setItems($items)
	{
		$this->items = $items;
		return $this;
	}

	/**
	 * @param int $maxItems
	 * @return $this
	 */
	public function setMaxItems($maxItems)
	{
		$this->maxItems = $maxItems;
		return $this;
	}

	/**
	 * @param int $minItems
	 * @return $this
	 */
	public function setMinItems($minItems)
	{
		$this->minItems = $minItems;
		return $this;
	}

	/**
	 * @param bool $uniqueItems
	 * @return $this
	 */
	public function setUniqueItems($uniqueItems)
	{
		$this->uniqueItems = $uniqueItems;
		return $this;
	}

	/**
	 * @param int $maxProperties
	 * @return $this
	 */
	public function setMaxProperties($maxProperties)
	{
		$this->maxProperties = $maxProperties;
		return $this;
	}

	/**
	 * @param int $minProperties
	 * @return $this
	 */
	public function setMinProperties($minProperties)
	{
		$this->minProperties = $minProperties;
		return $this;
	}

	/**
	 * @param string[]|array $required
	 * @return $this
	 */
	public function setRequired($required)
	{
		$this->required = $required;
		return $this;
	}

	/**
	 * @param bool|JsonSchema $additionalProperties
	 * @return $this
	 */
	public function setAdditionalProperties($additionalProperties)
	{
		$this->additionalProperties = $additionalProperties;
		return $this;
	}

	/**
	 * @param JsonSchema[] $definitions
	 * @return $this
	 */
	public function setDefinitions($definitions)
	{
		$this->definitions = $definitions;
		return $this;
	}

	/**
	 * @param JsonSchema[] $properties
	 * @return $this
	 */
	public function setProperties($properties)
	{
		$this->properties = $properties;
		return $this;
	}

	/**
	 * @param JsonSchema[] $patternProperties
	 * @return $this
	 */
	public function setPatternProperties($patternProperties)
	{
		$this->patternProperties = $patternProperties;
		return $this;
	}

	/**
	 * @param JsonSchema[]|string[][]|array[] $dependencies
	 * @return $this
	 */
	public function setDependencies($dependencies)
	{
		$this->dependencies = $dependencies;
		return $this;
	}

	/**
	 * @param array $enum
	 * @return $this
	 */
	public function setEnum($enum)
	{
		$this->enum = $enum;
		return $this;
	}

	/**
	 * @param array $type
	 * @return $this
	 */
	public function setType($type)
	{
		$this->type = $type;
		return $this;
	}

	/**
	 * @param string $format
	 * @return $this
	 */
	public function setFormat($format)
	{
		$this->format = $format;
		return $this;
	}

	/**
	 * @param string $ref
	 * @return $this
	 */
	public function setRef($ref)
	{
		$this->ref = $ref;
		return $this;
	}

	/**
	 * @param JsonSchema[]|array $allOf
	 * @return $this
	 */
	public function setAllOf($allOf)
	{
		$this->allOf = $allOf;
		return $this;
	}

	/**
	 * @param JsonSchema[]|array $anyOf
	 * @return $this
	 */
	public function setAnyOf($anyOf)
	{
		$this->anyOf = $anyOf;
		return $this;
	}

	/**
	 * @param JsonSchema[]|array $oneOf
	 * @return $this
	 */
	public function setOneOf($oneOf)
	{
		$this->oneOf = $oneOf;
		return $this;
	}

	/**
	 * @param JsonSchema $not Core schema meta-schema
	 * @return $this
	 */
	public function setNot($not)
	{
		$this->not = $not;
		return $this;
	}
}

