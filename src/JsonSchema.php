<?php

namespace Swaggest\JsonSchema;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Structure\ClassStructure;
use Swaggest\JsonSchema\Structure\SchemaStructure;


class JsonSchema extends SchemaStructure {
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

	/** @var JsonSchema|JsonSchema[] */
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

	/** @var JsonSchema */
	public $not;

	/**
	 * @param Properties|static $properties
	 * @param Schema $ownerSchema
	 */
	public static function setUpProperties($properties, Schema $ownerSchema)
	{
		$properties->id = JsonSchema::string();
		$properties->schema = JsonSchema::string();
		$ownerSchema->addPropertyMapping('$schema', self::names()->schema);
		$properties->title = JsonSchema::string();
		$properties->description = JsonSchema::string();
		$properties->default = new Schema();
		$properties->multipleOf = JsonSchema::number();
		$properties->multipleOf->minimum = 0;
		$properties->multipleOf->exclusiveMinimum = true;
		$properties->maximum = JsonSchema::number();
		$properties->exclusiveMaximum = JsonSchema::boolean();
		$properties->minimum = JsonSchema::number();
		$properties->exclusiveMinimum = JsonSchema::boolean();
		$properties->maxLength = JsonSchema::integer();
		$properties->maxLength->minimum = 0;
		$properties->minLength = new Schema();
		$properties->minLength->allOf[0] = JsonSchema::integer();
		$properties->minLength->allOf[0]->minimum = 0;
		$properties->minLength->allOf[1] = new Schema();
		$properties->pattern = JsonSchema::string();
		$properties->additionalItems = new Schema();
		$properties->additionalItems->anyOf[0] = JsonSchema::boolean();
		$properties->additionalItems->anyOf[1] = JsonSchema::schema();
		$properties->items = new Schema();
		$properties->items->anyOf[0] = JsonSchema::schema();
		$properties->items->anyOf[1] = JsonSchema::arr();
		$properties->items->anyOf[1]->items = JsonSchema::schema();
		$properties->items->anyOf[1]->minItems = 1;
		$properties->maxItems = JsonSchema::integer();
		$properties->maxItems->minimum = 0;
		$properties->minItems = new Schema();
		$properties->minItems->allOf[0] = JsonSchema::integer();
		$properties->minItems->allOf[0]->minimum = 0;
		$properties->minItems->allOf[1] = new Schema();
		$properties->uniqueItems = JsonSchema::boolean();
		$properties->maxProperties = JsonSchema::integer();
		$properties->maxProperties->minimum = 0;
		$properties->minProperties = new Schema();
		$properties->minProperties->allOf[0] = JsonSchema::integer();
		$properties->minProperties->allOf[0]->minimum = 0;
		$properties->minProperties->allOf[1] = new Schema();
		$properties->required = JsonSchema::arr();
		$properties->required->items = JsonSchema::string();
		$properties->required->uniqueItems = true;
		$properties->required->minItems = 1;
		$properties->additionalProperties = new Schema();
		$properties->additionalProperties->anyOf[0] = JsonSchema::boolean();
		$properties->additionalProperties->anyOf[1] = JsonSchema::schema();
		$properties->definitions = JsonSchema::object();
		$properties->definitions->additionalProperties = JsonSchema::schema();
		$properties->properties = JsonSchema::object();
		$properties->properties->additionalProperties = JsonSchema::schema();
		$properties->patternProperties = JsonSchema::object();
		$properties->patternProperties->additionalProperties = JsonSchema::schema();
		$properties->dependencies = JsonSchema::object();
		//$properties->dependencies->additionalProperties = JsonSchema::schema();
		$properties->dependencies->additionalProperties = new Schema();
		$properties->dependencies->additionalProperties->anyOf[0] = JsonSchema::schema();
		$properties->dependencies->additionalProperties->anyOf[1] = JsonSchema::arr();
		$properties->dependencies->additionalProperties->anyOf[1]->items = JsonSchema::string();
		$properties->dependencies->additionalProperties->anyOf[1]->uniqueItems = true;
		$properties->dependencies->additionalProperties->anyOf[1]->minItems = 1;
		$properties->enum = JsonSchema::arr();
		$properties->enum->uniqueItems = true;
		$properties->enum->minItems = 1;
		$properties->type = new Schema();
		$properties->type->anyOf[0] = new Schema();
		$properties->type->anyOf[0]->enum = array (
		  0 => 'array',
		  1 => 'boolean',
		  2 => 'integer',
		  3 => 'null',
		  4 => 'number',
		  5 => 'object',
		  6 => 'string',
		);
		$properties->type->anyOf[1] = JsonSchema::arr();
		$properties->type->anyOf[1]->items = new Schema();
		$properties->type->anyOf[1]->items->enum = array (
		  0 => 'array',
		  1 => 'boolean',
		  2 => 'integer',
		  3 => 'null',
		  4 => 'number',
		  5 => 'object',
		  6 => 'string',
		);
		$properties->type->anyOf[1]->uniqueItems = true;
		$properties->type->anyOf[1]->minItems = 1;
		$properties->format = JsonSchema::string();
		$properties->ref = JsonSchema::string();
		$ownerSchema->addPropertyMapping('$ref', self::names()->ref);
		$properties->allOf = JsonSchema::arr();
		$properties->allOf->items = JsonSchema::schema();
		$properties->allOf->minItems = 1;
		$properties->anyOf = JsonSchema::arr();
		$properties->anyOf->items = JsonSchema::schema();
		$properties->anyOf->minItems = 1;
		$properties->oneOf = JsonSchema::arr();
		$properties->oneOf->items = JsonSchema::schema();
		$properties->oneOf->minItems = 1;
		$properties->not = JsonSchema::schema();
		$ownerSchema->type = 'object';
		$ownerSchema->dependencies = array (
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
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
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
	 * @return string
	 */
	public function getSchema()
	{
		return $this->schema;
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
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
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
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
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

	public function getDefault()
	{
		return $this->default;
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
	 * @return float
	 */
	public function getMultipleOf()
	{
		return $this->multipleOf;
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
	 * @return float
	 */
	public function getMaximum()
	{
		return $this->maximum;
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
	 * @return bool
	 */
	public function getExclusiveMaximum()
	{
		return $this->exclusiveMaximum;
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
	 * @return float
	 */
	public function getMinimum()
	{
		return $this->minimum;
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
	 * @return bool
	 */
	public function getExclusiveMinimum()
	{
		return $this->exclusiveMinimum;
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
	 * @return int
	 */
	public function getMaxLength()
	{
		return $this->maxLength;
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
	 * @return int
	 */
	public function getMinLength()
	{
		return $this->minLength;
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
	 * @return string
	 */
	public function getPattern()
	{
		return $this->pattern;
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
	 * @return bool|JsonSchema
	 */
	public function getAdditionalItems()
	{
		return $this->additionalItems;
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
	 * @return JsonSchema|JsonSchema[]|array
	 */
	public function getItems()
	{
		return $this->items;
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
	 * @return int
	 */
	public function getMaxItems()
	{
		return $this->maxItems;
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
	 * @return int
	 */
	public function getMinItems()
	{
		return $this->minItems;
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
	 * @return bool
	 */
	public function getUniqueItems()
	{
		return $this->uniqueItems;
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
	 * @return int
	 */
	public function getMaxProperties()
	{
		return $this->maxProperties;
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
	 * @return int
	 */
	public function getMinProperties()
	{
		return $this->minProperties;
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
	 * @return string[]|array
	 */
	public function getRequired()
	{
		return $this->required;
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
	 * @return bool|JsonSchema
	 */
	public function getAdditionalProperties()
	{
		return $this->additionalProperties;
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
	 * @return JsonSchema[]
	 */
	public function getDefinitions()
	{
		return $this->definitions;
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
	 * @return JsonSchema[]
	 */
	public function getProperties()
	{
		return $this->properties;
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
	 * @return JsonSchema[]
	 */
	public function getPatternProperties()
	{
		return $this->patternProperties;
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
	 * @return JsonSchema[]|string[][]|array[]
	 */
	public function getDependencies()
	{
		return $this->dependencies;
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
	 * @return array
	 */
	public function getEnum()
	{
		return $this->enum;
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
	 * @return array
	 */
	public function getType()
	{
		return $this->type;
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
	 * @return string
	 */
	public function getFormat()
	{
		return $this->format;
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
	 * @return string
	 */
	public function getRef()
	{
		return $this->ref;
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
	 * @return JsonSchema[]|array
	 */
	public function getAllOf()
	{
		return $this->allOf;
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
	 * @return JsonSchema[]|array
	 */
	public function getAnyOf()
	{
		return $this->anyOf;
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
	 * @return JsonSchema[]|array
	 */
	public function getOneOf()
	{
		return $this->oneOf;
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
	 * @return JsonSchema
	 */
	public function getNot()
	{
		return $this->not;
	}

	/**
	 * @param JsonSchema $not
	 * @return $this
	 */
	public function setNot($not)
	{
		$this->not = $not;
		return $this;
	}
}

