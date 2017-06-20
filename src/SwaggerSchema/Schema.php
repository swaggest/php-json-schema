<?php
/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as the splendid AI will throw them away with no mercy.
 */

namespace Swaggest\JsonSchema\SwaggerSchema;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema as JsonBasicSchema;
use Swaggest\JsonSchema\Structure\ClassStructure;


class Schema extends ClassStructure {
	/** @var string */
	public $ref;

	/** @var string */
	public $format;

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

	/** @var array */
	public $enum;

	/** @var Schema|bool */
	public $additionalProperties;

	/** @var array */
	public $type;

	/** @var Schema|Schema[]|array */
	public $items;

	/** @var Schema[]|array */
	public $allOf;

	/** @var Schema[] */
	public $properties;

	/** @var string */
	public $discriminator;

	/** @var bool */
	public $readOnly;

	/** @var Xml */
	public $xml;

	/** @var ExternalDocs information about external documentation */
	public $externalDocs;

	public $example;

	/**
	 * @param Properties|static $properties
	 * @param JsonBasicSchema $ownerSchema
	 */
	public static function setUpProperties($properties, JsonBasicSchema $ownerSchema)
	{
		$properties->ref = JsonBasicSchema::string();
		$ownerSchema->addPropertyMapping('$ref', self::names()->ref);
		$properties->format = JsonBasicSchema::string();
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
		$properties->enum = JsonBasicSchema::arr();
		$properties->enum->minItems = 1;
		$properties->enum->uniqueItems = true;
		$properties->additionalProperties = new JsonBasicSchema();
		$properties->additionalProperties->anyOf[0] = Schema::schema();
		$properties->additionalProperties->anyOf[1] = JsonBasicSchema::boolean();
		$properties->additionalProperties->default = (object)array (
		);
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
		$properties->items = new JsonBasicSchema();
		$properties->items->anyOf[0] = Schema::schema();
		$properties->items->anyOf[1] = JsonBasicSchema::arr();
		$properties->items->anyOf[1]->items = Schema::schema();
		$properties->items->anyOf[1]->minItems = 1;
		$properties->items->default = (object)array (
		);
		$properties->allOf = JsonBasicSchema::arr();
		$properties->allOf->items = Schema::schema();
		$properties->allOf->minItems = 1;
		$properties->properties = JsonBasicSchema::object();
		$properties->properties->additionalProperties = Schema::schema();
		$properties->properties->default = (object)array (
		);
		$properties->discriminator = JsonBasicSchema::string();
		$properties->readOnly = JsonBasicSchema::boolean();
		$properties->readOnly->default = false;
		$properties->xml = Xml::schema();
		$properties->externalDocs = ExternalDocs::schema();
		$properties->example = new JsonBasicSchema();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new JsonBasicSchema();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->description = 'A deterministic version of a JSON Schema object.';
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
	 * @param string $format
	 * @return $this
	 */
	public function setFormat($format)
	{
		$this->format = $format;
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
	 * @param array $enum
	 * @return $this
	 */
	public function setEnum($enum)
	{
		$this->enum = $enum;
		return $this;
	}

	/**
	 * @param Schema|bool $additionalProperties
	 * @return $this
	 */
	public function setAdditionalProperties($additionalProperties)
	{
		$this->additionalProperties = $additionalProperties;
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
	 * @param Schema|Schema[]|array $items
	 * @return $this
	 */
	public function setItems($items)
	{
		$this->items = $items;
		return $this;
	}

	/**
	 * @param Schema[]|array $allOf
	 * @return $this
	 */
	public function setAllOf($allOf)
	{
		$this->allOf = $allOf;
		return $this;
	}

	/**
	 * @param Schema[] $properties
	 * @return $this
	 */
	public function setProperties($properties)
	{
		$this->properties = $properties;
		return $this;
	}

	/**
	 * @param string $discriminator
	 * @return $this
	 */
	public function setDiscriminator($discriminator)
	{
		$this->discriminator = $discriminator;
		return $this;
	}

	/**
	 * @param bool $readOnly
	 * @return $this
	 */
	public function setReadOnly($readOnly)
	{
		$this->readOnly = $readOnly;
		return $this;
	}

	/**
	 * @param Xml $xml
	 * @return $this
	 */
	public function setXml($xml)
	{
		$this->xml = $xml;
		return $this;
	}

	/**
	 * @param ExternalDocs $externalDocs information about external documentation
	 * @return $this
	 */
	public function setExternalDocs($externalDocs)
	{
		$this->externalDocs = $externalDocs;
		return $this;
	}

	/**
	 * @param $example
	 * @return $this
	 */
	public function setExample($example)
	{
		$this->example = $example;
		return $this;
	}
}

